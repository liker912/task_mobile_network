<?php
include "config.php";
/**
 * ** Note:
 * "SKU" = Stock Keeping Unit - this is unique identifier for a kind of item.
 * It can only appear once in each warehouse (eg if "shirt-blk" is sku for Black Shirt - then it can be seen only once in each warehouse).
 ** Note 2:
 * Although the data set is very small, assume that the real data table can contain a lot of data (100k records or more).
 *
 * Questions:
 *
 * 1. Write an SQL query to find all items (by SKU) that are present in both warehouse "Warehouse 1" and "Warehouse 2"
 * The data set to return: sku, description, quantity_1 (from Warehouse 1) and quantity_2 (from Warehouse 2)
 *
 * 2. Write an SQL query to find all items present only in a single warehouse.
 * Return full row for the matching items.
 *
 * 3. What would you improve in this table structure to optimize the perdformance of these queries?
 */
$conn = new mysqli($servername, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "======================================================================================================".PHP_EOL;
echo "                  Question # 1".PHP_EOL;
echo "======================================================================================================".PHP_EOL;
$sql = "SELECT w1.sku, w1.description, w1.quantity as quantity_1, w2.quantity as quantity_2 FROM items as w1 
        INNER JOIN items as w2 ON w1.sku = w2.sku WHERE w1.warehouse = 'Warehouse 1' and w2.warehouse = 'Warehouse 2' ";
$result = $conn->query($sql);
if (!$result) {
    trigger_error('Invalid query: ' . $conn->error);
}

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "sku: " . $row['sku'] . "\t" . "description: " .
            $row['description'] . "\t" . "quantity_1: " . $row['quantity_1'] . "\t"
            . "quantity_2: " . $row['quantity_2'] . PHP_EOL;
    }
}
echo PHP_EOL;

echo "======================================================================================================".PHP_EOL;
echo "                  Question # 2".PHP_EOL;
echo "======================================================================================================".PHP_EOL;

// Ypu can change 'Warehouse 2' to 'Warehouse 1', etc
$sql = "SELECT * FROM items where warehouse = 'Warehouse 2' ORDER BY sku ASC";
$result = $conn->query($sql);
if (!$result) {
    trigger_error('Invalid query: ' . $conn->error);
}

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "warehouse: " . $row['warehouse'] . "\t" . "sku: " . $row['sku'] . "\t" . "description: " .
            $row['description'] . "\t" . "quantity: " . $row['quantity'] . "\t" . "price: " . $row['price'] . PHP_EOL;
    }
}

/**
 * question #3
 *
 * Created different tables: one with warehouse and another with scu. Created relationship between table Items and new
 * tables. Created indexes for fields
 *
*/
