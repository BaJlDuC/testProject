<?php
require 'dbFunctions.php';

$vkId = $_GET['userId'];

if (IsUserExistInDataBase($vkId))
{
    echo 'exist, updating data from user...';
    UpdateUserInDataBase($vkId);
}
else
{
    AddUserInDataBase($vkId);
}