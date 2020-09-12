<?php

$link = ConnectDataBase()
        or die("Не удалось подключиться к базе данных " . mysqli_error($link));

$uniqueUserId = $_SESSION['userInfo']['id'];

$query = "SELECT * FROM checkListItem$uniqueUserId";
$result = $link->query($query);

if ($row = $result->fetch_assoc() <> 0)
{
    $listItemName = "";
    $idString = "id_checkListItem$uniqueUserId";

    echo '<div class="checkListItems">';
    while($row = $result->fetch_assoc()) { //!!!!!!!!!!!!!!!!!!сделать так тчобы имена клались в массив
        if ($row[$idString] == 1) {
            echo '<p class="checkListItemName">' . '<a href="checkListItem.php?listElementName=' . $row['name'] . '"> ' . $row["name"] . "</a><br></p>";
            echo '<p class="checkListItemDescription">' . $row["description"] . "<br></p>";
            $listItemName = $row["name"];
        }
        else if ($listItemName != $row["name"])
        {
            echo '<p class="checkListItemName">' . '<a href="checkListItem.php?listElementName=' . $row['name'] . '"> ' . $row["name"] . "</a><br></p>";
            echo '<p class="checkListItemDescription">' . $row["description"] . "<br></p>";
            $listItemName = $row["name"];
        }
        else
        {
            $listItemName = $row["name"];
        }
    }
    echo '</div>';
}