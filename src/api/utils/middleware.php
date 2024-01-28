<?php

// middleware
namespace api\utils;

session_start();

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Selective\SameSiteCookie\SameSiteCookieConfiguration;
use Selective\SameSiteCookie\SameSiteCookieMiddleware;
use Slim\App;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;
use Throwable;

/**
 * Middleware
 * Define all middleware functions
 */
class middleware
{
    /**
     * Constructor for middleware
     * @param App $app - Slim App
     */
    public function __construct(App $app)
    {
        $this->baseMiddleware($app);
        $this->sameSiteConfig($app);
        $this->errorHandling($app);
        $this->returnAsJSON($app);
    }

    /**
     * Base middleware
     * @param App $app - Slim App
     */
    public function baseMiddleware(App $app): void
    {
        $app->addBodyParsingMiddleware();
        $app->addRoutingMiddleware();
    }

    /**
     * SameSite Cookie Configuration
     * @param App $app - Slim App
     */
    public function sameSiteConfig(App $app): void
    {
        $ssConfig = new SameSiteCookieConfiguration(["same_site" => "strict"]);
        $app->add(new SameSiteCookieMiddleware($ssConfig));
    }

    /**
     * Return all responses as JSON
     * @param App $app - Slim App
     */
    public function returnAsJSON(App $app): void
    {
        $app->add(function ($request, $handler) {
            $response = $handler->handle($request);
            $contentType = $response->getHeaderLine("Content-Type");
            if (empty($contentType) || $contentType === "text/html") {
                return $response->withHeader("Content-Type", "application/json");
            }
            return $response;
        });
    }

    /**
     * Error handling
     * @param App $app - Slim App
     */
    public function errorHandling(App $app): void
    {
        $app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
            try {
                return $handler->handle($request);
            } catch (HttpNotFoundException $httpException) {
                $response = (new Response())->withStatus(404);
                $response->getBody()->write(json_encode(array("status" => "404", "message" => $request->getUri()->getPath() . " not found")));
                return $response;
            } catch (HttpMethodNotAllowedException $httpException) {
                $response = (new Response())->withStatus(405);
                $response->getBody()->write(json_encode(array("status" => "405", "message" => "Method not allowed")));
                return $response;
            } catch (HttpInternalServerErrorException $exception) {
                $response = (new Response())->withStatus(500);
                $response->getBody()->write(json_encode(array("status" => "500", "message" => $exception->getMessage())));
                return $response;
            }
        });


        $errorMiddleware = $app->addErrorMiddleware(true, true, true);


        $errorHandler = $errorMiddleware->getDefaultErrorHandler();

        $errorMiddleware->setDefaultErrorHandler(function (ServerRequestInterface $request, Throwable $exception,
                                                           bool                   $displayErrorDetails,
                                                           bool                   $logErrors,
                                                           bool                   $logErrorDetails
        ) use ($app, $errorHandler) {
            $statusCode = $exception->getCode() ?: 500;

            // Create a JSON response with the error message
            $response = $app->getResponseFactory()->createResponse($statusCode);
            $response->getBody()->write(json_encode(['error' => $exception->getMessage()]));

            return $response;
        });
    }
}
