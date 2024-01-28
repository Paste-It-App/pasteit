<?php

namespace api\paste;
require_once "pasteData.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

class pasteRoutes
{
    private pasteData $pasteData;

    /**
     * constructor used to instantiate a base paste routes, to be used in the index.php file.
     * @param App $app - the slim app used to create the routes
     */
    public function __construct(App $app)
    {
        $this->pasteData = new pasteData();
        $this->createRoutes($app);
    }

    /**
     * creates the routes for the pasteData
     * @param App $app - the slim app used to create the routes
     * @return void - returns nothing
     */
    public function createRoutes(App $app): void
    {
        $app->post("/paste", function (Request $request, Response $response)
        {
            $body = $request->getParsedBody();
            if (empty($body["pasteContent"]) || empty($body["pasteName"]) || empty($body["language"]))
            {
                $response->getBody()->write(json_encode(array("error", "Only some of the data was sent")));
                return $response->withStatus(400);
            }

            $data = $this->pasteData->addPaste($body["pasteContent"], $body["pasteName"], $body["language"], $body["tags"]);

            if (is_string($data))
            {
                $response->getBody()->write(json_encode(array("uniqueID", $data)));
                return $response;
            }

            $response->getBody()->write(json_encode(array("error", "Something went wrong")));
            return $response->withStatus(500);

        });

        $app->get("/paste/{id}", function (Request $request, Response $response, array $args)
        {
            if (empty($args["id"]))
            {
                $response->getBody()->write(json_encode(array("error" => "Only some of the data was sent")));
                return $response->withStatus(400);
            }

            $data = $this->pasteData->getPaste($args["id"]);

            if (!is_null($data))
            {
                $response->getBody()->write(json_encode($data));
                return $response;
            }

            $response->getBody()->write(json_encode(array("error" => "Paste not found", "id" => $args["id"])));
            return $response->withStatus(404);
        });
    }
}

