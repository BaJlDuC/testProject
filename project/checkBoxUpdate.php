<?php
require_once 'dbFunctions.php';

$infoForUpd = $_GET['recordIndex'];

$tableName = $infoForUpd[0];
$recordId = $infoForUpd[1];

$link = ConnectDataBase();

$query = "SELECT state FROM $tableName WHERE id_$tableName = $recordId";
$result = $link->query($query);
$row = $result->fetch_assoc();

if ($row['state'] == 1)
{
    $query = "UPDATE $tableName 
SET state = 0
WHERE id_$tableName = $recordId";
    $link->query($query);
}
else{
    $query = "UPDATE $tableName 
SET state = 1
WHERE id_$tableName = $recordId";
    $link->query($query);
}