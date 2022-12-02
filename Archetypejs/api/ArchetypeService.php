<?php

require_once (__DIR__ . '/../db/ArchetypeAccessor.php');
require_once (__DIR__ . '/../entity/Archetype.php');
require_once (__DIR__ . '/../ChromePhp.php');


$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    doGet();
} else if ($method === "POST") {
    doPost();
} else if ($method === "DELETE") {
    doDelete();
} else if ($method === "PUT") {
    doPut();
}

function doGet() {
    // individual
    if (isset($_GET['itemid'])) { 
        ChromePhp::log("Sorry, individual gets not allowed!");
    }
    // collection
    else {
        try {
            $ar = new ArchetypeAccessor();
            $results = $ar->getAllItems();
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            echo $results;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    }
}

function doDelete() {
    if (isset($_GET['itemid'])) { 
        $itemID = $_GET['itemid']; 
        $archetypeObj = new Archetype($itemID, "dummyCat", "UR", 1, "eldlitch but not worth revealing");

        // delete the object from DB
        $ar = new ArchetypeAccessor();
        $success = $ar->deleteItem($archetypeObj);
        echo $success;
    } else {
        ChromePhp::log("Sorry, bulk deletes are illigal");
    }
}

// aka CREATE
function doPost() {
    if (isset($_GET['itemid'])) { 
        // The details of the item to insert will be in the request body.
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);
        ChromePhp::log($contents);
        // create a Archetype object
        $arObj = new Archetype($contents['ArchetypeID'], 
                $contents['ArchetypeName'], 
                $contents['Rarity'], 
                $contents['Complication'], 
                $contents['Description']);

        // add the object to DB
        $ar = new ArchetypeAccessor();
        $success = $ar->insertItem($arObj);
        echo $success;
    } else {
        ChromePhp::log("Sorry, we cant make every record one archetype");
        echo $_GET['itemid'];
    }
}

// aka UPDATE
function doPut() {
    if (isset($_GET['itemid'])) { 
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        // create a Archetype
        $arObj = new Archetype($contents['ArchetypeID'], 
                $contents['ArchetypeName'], 
                $contents['Rarity'], 
                $contents['Complication'], 
                $contents['Description']);

        // update the object in the  DB
        $ar = new ArchetypeAccessor();
        $success = $ar->updateItem($arObj);
        echo $success;
    } else {
        ChromePhp::log("Sorry, bulk updates are also illigal");
    }
}