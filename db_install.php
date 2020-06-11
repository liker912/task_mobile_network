<?php
include "config.php";
// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// create db if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbName;";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully".PHP_EOL;
} else {
    echo "Error creating database: " . $conn->error.PHP_EOL;
}
$conn->close();


// create new connection to database
$conn = new mysqli($servername, $username, $password, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error.PHP_EOL);
}


// sql to create table countries
$sql = "CREATE TABLE IF NOT EXISTS countries (
id INT(6) UNSIGNED AUTO_INCREMENT,
code VARCHAR(5) NOT NULL,
country_name VARCHAR(100) NOT NULL,
iso VARCHAR(15) NOT NULL,
idx VARCHAR(100),
PRIMARY KEY (id), INDEX idx (idx)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table countries created successfully".PHP_EOL;
} else {
    echo "Error creating table: " . $conn->error.PHP_EOL;
}


// sql to create table countries
$sql = "CREATE TABLE IF NOT EXISTS operators (
id INT(10) UNSIGNED AUTO_INCREMENT,
mcc VARCHAR(30) NOT NULL,
mnc VARCHAR(30) NOT NULL,
brand VARCHAR(100) NOT NULL,
operator VARCHAR(100) NOT NULL,
bands VARCHAR(200) NOT NULL,
idx VARCHAR(100),
PRIMARY KEY (id), INDEX idx (idx)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table operators created successfully".PHP_EOL;
} else {
    echo "Error creating table: " . $conn->error.PHP_EOL;
}

// create tables for task #2
                    $sql = "CREATE TABLE IF NOT EXISTS items (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  warehouse varchar(100) NOT NULL DEFAULT '',
  sku varchar(100) NOT NULL DEFAULT '',
  description varchar(255) NOT NULL DEFAULT '',
  quantity int(11) NOT NULL DEFAULT '0',
  price decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (id)
);";

if ($conn->query($sql) === TRUE) {
    echo "Table ITEMS created successfully".PHP_EOL;
} else {
    echo "Error creating table: " . $conn->error.PHP_EOL;
}

// clear data first
$sql = 'TRUNCATE TABLE items';
if ($conn->query($sql) === TRUE) {
    echo "items clear successfully".PHP_EOL;
} else {
    echo "Error: " . $sql . PHP_EOL . $conn->error;
}

//insert data
$sql = "INSERT INTO items (warehouse,sku,description,quantity,price) VALUES
('Warehouse 1','shirt-blk','Black shirt',10,100),
('Warehouse 2','shirt-blk','Black shirt',20,100),
('Warehouse 1','shirt-wht','White shirt',10,100),
('Warehouse 2','shirt-grn','Green shirt',10,100),
('Warehouse 3','shirt-red','Red shirt',10,100),
('Warehouse 1','skirt-blk','Black skirt',10,100),
('Warehouse 2','skirt-blk','Black skirt',10,100),
('Warehouse 1','dress-blk','Black dress',10,100);";


if ($conn->query($sql) === TRUE) {
    echo "items INSERT successfully" . PHP_EOL;
} else {
    echo "Error: " . $sql . PHP_EOL . $conn->error;
}

$conn->close();

