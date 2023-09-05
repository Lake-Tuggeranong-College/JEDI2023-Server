<?php
include "config.php";
include "commonFunctions.php";

$api_key = $userName = $moduleName = $moduleData = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    upload();
} else {
    echo("This page is only accessible via POSTing from ESP32s...");
}