<?php

namespace api\paste;

include __DIR__."/../utils/config.php";

use function api\utils\dbConn;

class pasteData
{


    /**
     * Function to add a paste to the db
     * @param string $pasteContent - the paste
     * @param string $pasteName - name of the paste
     * @param string $language - the programming language
     * @param string $ags - tags of the paste
     * @return string - unique id for url
     */
    public function addPaste(string $pasteContent, string $pasteName, string $language, string $ags): string
    {
       $conn = dbConn();
       $uniqueID = uniqid();

       $stmt = $conn->prepare("INSERT INTO pastes (pasteName, language, uniqueID, tags, data) VALUES(:pasteName, :language, :uniqueID, :tags, :data)");
       $stmt->bindParam(":pasteName", $pasteName);
       $stmt->bindParam(":language", $language);
       $stmt->bindParam(":uniqueID", $uniqueID);
       $stmt->bindParam(":tags", $tags);
       $stmt->bindParam(":data", $pasteContent);
       $stmt->execute();

       return $uniqueID;

    }

    /**
     * Get the current paste
     * @param string $id
     * @return array | null pasteData
     */
    public function getPaste(string $id): ?array
    {
        $conn = dbConn();
        $stmt = $conn->prepare("SELECT * FROM pastes WHERE uniqueID=:unique");
        $stmt->bindParam(":unique", $id);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        $data["data"] = stripslashes($data["data"]);

       return $data;

    }
}