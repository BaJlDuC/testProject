<?php

$link = ConnectDataBase()
        or die("Не удалось подключиться к базе данных " . mysqli_error($link));

$uniqueUserId = $_SESSION['userInfo']['id'];

$query = "SELECT * FROM checkListItem$uniqueUserId";
$result = $link->query($query);
if (!$result)
{
    echo '<p class="loading">Загрузка...</p>';
    header('Refresh: 0');
}
else if ($row = $result->fetch_assoc() <> 0)
{
    $listItemName = "";
    $listItemNames = array();
    $idString = "id_checkListItem$uniqueUserId";

    echo '<div class="checkListItems">';
    while($row = $result->fetch_assoc()) { //!!!!!!!!!!!!!!!!!!сделать так тчобы имена клались в массив
        if (!in_array($row['name'], $listItemNames)) {
            echo '<p class="checkListItemName">' . '<a href="checkListItem.php?listElementName=' . $row['name'] . '"> ' . $row["name"] . "</a><br></p>";
            echo '<p class="checkListItemDescription">' . $row["description"] . "<br></p>";
            array_push($listItemNames, $row['name']);
        }
    }
    echo '</div>';
}