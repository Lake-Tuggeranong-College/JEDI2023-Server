<?php

include "config.php";

$api_key = $userName = $moduleName = $moduleData = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Takes raw data from the request
    $json = file_get_contents('php://input');
    // Converts it into a PHP object
    $data = json_decode($json);

    // example of extracting one element of json object
    // $api_key = $data->api_key;
    $userName = $data->userName;
    $moduleData = $data->moduleData;
    $moduleName = $data->moduleName;
    $api_key = $data->api_key;
    date_default_timezone_set('Australia/Canberra');
    $date = date("Y-m-d H:i:s");

    //DO NOT CHANGE THIS DATE CODE, MUST STAY SAME TO WORK WITH MYSQL
    $sql = "INSERT INTO moduleData (moduleName, moduleData, userName, datePosted) VALUES (:moduleName, :moduleData, :userName,:datePosted)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':userName', $userName);
    $stmt->bindValue(':moduleData', $moduleData);
    $stmt->bindValue(':moduleName', $moduleName);
    $stmt->bindValue(':datePosted', $date);
    $stmt->execute();

    $query = $conn->query("SELECT command, hashedPassword FROM moduleCommands WHERE actuator='$moduleName'");
    $queryData = $query->fetch();
    $payload = $queryData["command"];
    $hashed_password = $queryData["hashedPassword"];

    if (password_verify($api_key, $hashed_password)) {
        $payloadJSON = ['command' => $payload];
    } else {
        $payloadJSON = ['command' => 'Password Error'];
    }
    header('Content-type: application/json');
    echo json_encode($payloadJSON);


    $conn->close();
} else {
    echo "This page is only accessible via POSTing from ESP32s..";
}

