<?php

$projectRoot = $_SERVER['DOCUMENT_ROOT'] . '/Archetypejs';
require_once 'dbconnector.php';
require_once ($projectRoot . '/entity/Archetype.php');

class ArchetypeAccessor {

    private $getByIDStatementString = "select * from archetypes where ArchetypeID = :ArchetypeID";
    private $deleteStatementString = "delete from archetypes where ArchetypeID = :ArchetypeID";
    private $insertStatementString = "insert INTO archetypes values (:ArchetypeID, :ArchetypeName, :Rarity, :Complication, :Description)";
    private $updateStatementString = "update archetypes set ArchetypeName = :ArchetypeName, Rarity = :Rarity, Complication = :Complication, Description = :Description where ArchetypeID = :ArchetypeID";
    private $conn = NULL;
    private $getByIDStatement = NULL;
    private $deleteStatement = NULL;
    private $insertStatement = NULL;
    private $updateStatement = NULL;

    // Constructor will throw exception if there is a problem with ConnectionManager,
    // or with the prepared statements.
    public function __construct() {
        $cm = new Connector();

        $this->conn = $cm->connect_db();
        if (is_null($this->conn)) {
            throw new Exception("no connection");
        }
        $this->getByIDStatement = $this->conn->prepare($this->getByIDStatementString);
        if (is_null($this->getByIDStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->deleteStatement = $this->conn->prepare($this->deleteStatementString);
        if (is_null($this->deleteStatement)) {
            throw new Exception("bad statement: '" . $this->deleteStatementString . "'");
        }

        $this->insertStatement = $this->conn->prepare($this->insertStatementString);
        if (is_null($this->insertStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->updateStatement = $this->conn->prepare($this->updateStatementString);
        if (is_null($this->updateStatement)) {
            throw new Exception("bad statement: '" . $this->updateStatementString . "'");
        }
    }

    /**
     * Gets Archetype items by executing a SQL "select" statement. An empty array
     * is returned if there are no results, or if the query contains an error.
     * 
     * @param String $selectString a valid SQL "select" statement
     * @return array Archetype objects
     */
    private function getItemsByQuery($selectString) {
        $result = [];

        try {
            $stmt = $this->conn->prepare($selectString);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $itemID = $r['ArchetypeID'];
                $itemName = $r['ArchetypeName'];
                $rarity = $r['Rarity'];
                $difficulty = $r['Complication'];
                $description = $r['Description'];
                $obj = new Archetype($itemID, $itemName, $rarity, $difficulty, $description);
                array_push($result, $obj);
            }
        }
        catch (Exception $e) {
            $result = [];
        }
        finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    /**
     * Gets all archetypes.
     * 
     * @return array Archetype objects, possibly empty
     */
    public function getAllItems() {
        return $this->getItemsByQuery("select * from archetypes");
    }

    /**
     * Gets the archetype with the specified ID.
     * 
     * @param Integer $id the ID of the item to retrieve 
     * @return the Archetype object with the specified ID, or NULL if not found
     */
    private function getItemByID($id) {
        $result = [];

        try {
            $this->getByIDStatement->bindParam(":ArchetypeID", $id);
            $this->getByIDStatement->execute();
            $dbresults = $this->getByIDStatement->fetch(PDO::FETCH_ASSOC); // not fetchAll

            if ($dbresults) {
                $itemID = $dbresults['ArchetypeID'];
                $itemName = $dbresults['ArchetypeName'];
                $rarity = $dbresults['Rarity'];
                $difficulty = $dbresults['Complication'];
                $description = $dbresults['Description'];
                $arch = new Archetype($itemID, $itemName, $rarity, $difficulty, $description);
                array_push($result, $arch);
            }
        }
        catch (Exception $e) {
            $result = NULL;
        }
        finally {
            if (!is_null($this->getByIDStatement)) {
                $this->getByIDStatement->closeCursor();
            }
        }

        return $result;
    }

    /**
     * Deletes a menu item.
     * @param Archetype $item an object whose ID is EQUAL TO the ID of the item to delete
     * @return boolean indicates whether the item was deleted
     */
    public function deleteItem($item) {
        $success = false;

        $arcID = $item->getItemID(); // only the ID is needed

        try {
            $this->deleteStatement->bindParam(":ArchetypeID", $arcID);
            $success = $this->deleteStatement->execute(); // this doesn't mean what you think it means
            $rc = $this->deleteStatement->rowCount();
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->deleteStatement)) {
                $this->deleteStatement->closeCursor();
            }
            return $success;
        }
    }

    /**
     * Inserts a archetype into the database.
     * 
     * @param Archetype $item an object of type Archetype
     * @return boolean indicates if the item was inserted
     */
    public function insertItem($item) {
        $success = false;

        $itemID = $item->getItemID();
        $itemName = $item->getItemName();
        $rarity = $item->getRarity();
        $difficulty = $item->getDifficulty();
        $description = $item->getDescription();

        try {
            $this->insertStatement->bindParam(":ArchetypeID", $itemID);
            $this->insertStatement->bindParam(":ArchetypeName", $itemName);
            $this->insertStatement->bindParam(":Rarity", $rarity);
            $this->insertStatement->bindParam(":Complication", $difficulty);
            $this->insertStatement->bindParam(":Description", $description);
            $success = $this->insertStatement->execute();// this doesn't mean what you think it means
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->insertStatement)) {
                $this->insertStatement->closeCursor();
            }
            return $success;
        }
    }

    /**
     * Updates a archetype in the database.
     * 
     * @param Archetype $item an object of type Archetype, the new values to replace the database's current values
     * @return boolean indicates if the item was updated
     */
    public function updateItem($item) {
        $success = false;

        $itemID = $item->getItemID();
        $itemName = $item->getItemName();
        $rarity = $item->getRarity();
        $difficulty = $item->getDifficulty();
        $description = $item->getDescription();

        try {
            $this->updateStatement->bindParam(":ArchetypeID", $itemID);
            $this->updateStatement->bindParam(":ArchetypeName", $itemName);
            $this->updateStatement->bindParam(":Rarity", $rarity);
            $this->updateStatement->bindParam(":Complication", $difficulty);
            $this->updateStatement->bindParam(":Description", $description);
            $success = $this->updateStatement->execute();
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->updateStatement)) {
                $this->updateStatement->closeCursor();
            }
            return $success;
        }
    }

}