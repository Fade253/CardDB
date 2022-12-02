<?php

class Archetype implements JsonSerializable {
    private $ArchetypeID;
    private $ArchetypeName;
    private $Rarity;
    private $Difficulty;
    private $Description;
    
    public function __construct($itemID, $itemName, $rarity, $difficulty, $description) {
        $this->ArchetypeID = $itemID;
        $this->ArchetypeName = $itemName;
        $this->Rarity = $rarity;
        $this->Difficulty = $difficulty;
        $this->Description = $description;
    }

    public function getItemID() {
        return $this->ArchetypeID;
    }

    public function getItemName() {
        return $this->ArchetypeName;
    }

    public function getDescription() {
        return $this->Description;
    }

    public function getRarity() {
        return $this->Rarity;
    }

    public function getDifficulty() {
        return $this->Difficulty;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}