<?php
include "config.php";


$countries = array();
$operators = array();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['idx'])) {
    // get data from table operators
    $sql = "SELECT * FROM operators where operators.idx = '".$_POST['idx']."' and operators.operator != ''";
    $result = $conn->query($sql);
    if (!$result) {
        trigger_error('Invalid query: ' . $conn->error);
    }

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($operators, array(
                "id" => $row["id"],
                "mcc" =>  $row["mcc"],
                "mnc" => $row["mnc"],
                "brand" => $row["brand"],
                "operator" => $row["operator"],
                "bands" => $row["bands"],
                "idx" => $row["idx"]

            ));
        }
    }

    echo json_encode($operators);
} else {
// get data from table countries
    $sql = "SELECT * FROM countries";
    $result = $conn->query($sql);
    if (!$result) {
        trigger_error('Invalid query: ' . $conn->error);
    }

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($countries, array(
                "id" => $row["id"],
                "code" => $row["code"],
                "country_name" => $row["country_name"],
                "iso" => $row["iso"],
                "idx" => $row["idx"]

            ));
        }
    }

    echo json_encode($countries);
}