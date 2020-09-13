<?php

function ConnectDataBase()
{
    $host = 'localhost';
    $database = 'mydb';
    $user = 'root';
    $password = "123456";

    return mysqli_connect($host, $user, $password, $database);
}

function IsUserExistInDataBase($id)
{
    $link = ConnectDataBase()
    or die("Не удалось подключиться к базе данных " . mysqli_error($link));
    $query = "SELECT * FROM user WHERE vkId=$id";
    $result = $link->query($query);
    $row = $result->fetch_assoc();
    if (empty($row))
    {
        return false;
    }
    return true;
}

function AddUserInDataBase($id)
{
    $link = ConnectDataBase()
    or die("Не удалось подключиться к базе данных " . mysqli_error($link));
    $query = "INSERT INTO user (vkId) VALUES ($id)";
    $link->query($query);

    $query = "CREATE TABLE checkBoxUnderCheckBox$id SELECT * FROM checkBoxUnderCheckBox;
              CREATE TABLE checkBox$id SELECT * FROM checkBox;
              CREATE TABLE checkListItem$id SELECT * FROM checkListItem;
              
              ALTER TABLE `mydb`.`checkbox$id` 
CHANGE COLUMN `id_checkBox` `id_checkBox$id` INT(11) NOT NULL AUTO_INCREMENT ,
CHANGE COLUMN `id_checkBoxUnderCheckBox` `id_checkBoxUnderCheckBox$id` INT(11) NULL DEFAULT NULL ,
ADD PRIMARY KEY (`id_checkBox$id`),
ADD INDEX `fk_idx` (`id_checkBoxUnderCheckBox$id` ASC) VISIBLE;


ALTER TABLE `mydb`.`checkboxundercheckbox$id` 
CHANGE COLUMN `id_checkBoxUnderCheckBox` `id_checkBoxUnderCheckBox$id` INT(11) NOT NULL AUTO_INCREMENT ,
ADD PRIMARY KEY (`id_checkBoxUnderCheckBox$id`);


ALTER TABLE `mydb`.`checklistitem$id` 
CHANGE COLUMN `id_checkListItem` `id_checkListItem$id` INT(11) NOT NULL AUTO_INCREMENT ,
CHANGE COLUMN `id_checkBox` `id_checkBox$id` INT(11) NOT NULL ,
ADD PRIMARY KEY (`id_checkListItem$id`),
ADD INDEX `fk_idx` (`id_checkBox$id` ASC) VISIBLE;


ALTER TABLE `mydb`.`checkbox$id` 
ADD CONSTRAINT `1fk$id`
  FOREIGN KEY (`id_checkBoxUnderCheckBox$id`)
  REFERENCES `mydb`.`checkboxundercheckbox$id` (`id_checkBoxUnderCheckBox$id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `mydb`.`checklistitem$id` 
ADD CONSTRAINT `2fk$id`
  FOREIGN KEY (`id_checkBox$id`)
  REFERENCES `mydb`.`checkbox$id` (`id_checkBox$id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE";
    $link->multi_query($query);
}

function UpdateUserInDataBase($id)
{
    $link = ConnectDataBase()
    or die("Не удалось подключиться к базе данных " . mysqli_error($link));
    $query = "SELECT * FROM checkBoxUnderCheckBox";
    $result = $link->query($query);

    $sourceDataUnderCheckBox = mysqli_num_rows($result);
    $sourceDataCheckBox = mysqli_num_rows($link->query("SELECT * FROM checkBox"));
    $sourceDataCheckList = mysqli_num_rows($link->query("SELECT * FROM checkListItem"));

    $query = "SELECT * FROM checkBoxUnderCheckBox$id";
    $currentDataUnderCheckBox = mysqli_num_rows($link->query($query));
    $query = "SELECT * FROM checkBox$id";
    $currentDataCheckBox = mysqli_num_rows($link->query($query));
    $query = "SELECT * FROM checkListItem$id";
    $currentDataCheckList = mysqli_num_rows($link->query($query));

    //продолжу

    if ($currentDataUnderCheckBox != $sourceDataUnderCheckBox) {
        echo 'update under checkBox';
        $query = "DELETE FROM checkBoxUnderCheckBox$id WHERE id_checkBoxUnderCheckBox$id <= $currentDataUnderCheckBox; INSERT INTO checkBoxUnderCheckBox$id
SELECT *
FROM checkBoxUnderCheckBox";
        $link->multi_query($query);
    }
    else if ($currentDataCheckBox != $sourceDataCheckBox) {
        echo 'checkbox update';
        $query = "DELETE FROM checkBox$id WHERE id_checkBox$id <= $currentDataCheckBox; INSERT INTO checkBox$id
SELECT *
FROM checkBox";
        $link->multi_query($query);
    }
    else if ($currentDataCheckList != $sourceDataCheckList) {
        $query = "DELETE FROM checkListItem$id WHERE id_checkListItem$id <= $currentDataCheckList; INSERT INTO checkListItem$id
SELECT *
FROM checkListItem";
        $link->multi_query($query);
    }
    else
    {
        echo 'nothing update';
    }
}