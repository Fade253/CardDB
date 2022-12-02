<?php
Class Connector{
function connect_db() {
    $db = new PDO("mysql:host=localhost;dbname=yu-gi-oh archetypes", "Richard", "Reaper253");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}
}
