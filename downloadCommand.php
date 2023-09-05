<?php
include "config.php";

$api_key = $userName = $moduleName = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Takes raw data from the request
    $json = file_get_contents("php://input");
    // Converts it into a PHP object
    $data = json_decode($json);

    // example of extracting one element of json object
    // $api_key = $data->api_key;
    // $userName = $data->userName;
    $moduleName = $data->moduleName;
    $api_key = $data->api_key;
    date_default_timezone_set("Australia/Canberra");
    $date = date("Y-m-d H:i:s");

    $sql = "SELECT command, hashedPassword FROM moduleCommands WHERE actuator='$moduleName'";
    $query = $conn->query($sql);
    $data = $query->fetch();
    $payload = $data["command"];
    $hashed_password = $data["hashedPassword"];
    echo $data;
    if (password_verify($api_key, $hashed_password)) {
        $payloadJSON = ["command" => $payload];
    } else {
        $payloadJSON = ["command" => "Password Error"];
    }
    header("Content-type: application/json");
    echo json_encode($payloadJSON);

    $conn->close();
} else {
    echo "This page is only accessible via POSTing from ESP32s...";
}
