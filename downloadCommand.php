<?php
include "config.php";
include "commonFunctions.php";

$api_key = $userName = $moduleName = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    download();
} else {
    echo "This page is only accessible via POSTing from ESP32s...";
}
