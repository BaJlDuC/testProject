<head>
<title>Главная</title>
<h2 class="main">Главная</h2>
    <link rel="stylesheet" href="css/style.css" media="all">
     <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
     <script type="text/javascript" src="js/dropDownMenu.js"></script>
     <script type="text/javascript" src="js/updateCheckbox.js"></script>
</head>
<body>

<?php

require 'dbFunctions.php';
session_start();

if (isset($_SESSION['userInfo'])) {
    echo '<div class="account"><div class="photo"><img src="' . $_SESSION['userInfo']['photo'] . '"/></div>';
    echo '<div class="userName"><p>' . $_SESSION['userInfo']['first_name'] . '</p></div>' .
        '<p class="logout"><a href="http://localhost?out">Выход</a></p></div>';

    if (isset($_GET['out'])) {
        session_unset();
        header('Location: index.php');
    }

    $listName = $_GET['listElementName'];
    echo '<h1 class="checkListBanner">'. $listName . '</h1>';

    $link = ConnectDataBase()
    or die("Не удалось подключиться к базе данных " . mysqli_error($link));

    $uniqueUserId = $_SESSION['userInfo']['id'];
    $query = "SELECT id_checkBox$uniqueUserId FROM checkListItem$uniqueUserId WHERE name='$listName'";
    $result = $link->query($query);
    $checkBoxIndexes = array();

    $idString = "id_checkBox$uniqueUserId";
    while($row = $result->fetch_assoc()) {
        array_push($checkBoxIndexes, $row[$idString]);
    }

    $checkBoxNames = array();
    echo '<div class=checkBoxList id="checkBoxListId">';
    foreach ($checkBoxIndexes as $checkBoxIndex)
    {
        $query = "SELECT * FROM checkBox$uniqueUserId WHERE id_checkBox$uniqueUserId=$checkBoxIndex";
        $result = $link->query($query);
        while($row = $result->fetch_assoc()) {
            $checkBoxId = "id_checkBox$uniqueUserId";
            if (!in_array($row['name'], $checkBoxNames)) {

                $checked = ($row['state'] == 1);
                echo '<div class="checkBoxItem">' . '<input type="checkbox" onclick="updateRecord('. "'checkBox$uniqueUserId', $row[$checkBoxId]" . ')" ';

                if($checked) {echo 'checked';}
                echo '>' . $row['name'] .
                    '<a href="#hidden'. $checkBoxIndex .'" onclick="view('. "'hidden$checkBoxIndex'". '); return false"; class="moreInfo"> подробнее</a> '.
                    '<div id="hidden'. $checkBoxIndex .'", style="display: none;">';
                echo $row['description'] . '<br>';

                $oneMoreQuery = "SELECT checkBox$uniqueUserId.name, checkBox$uniqueUserId.id_checkBoxUnderCheckBox$uniqueUserId, 
                          checkBoxUnderCheckBox$uniqueUserId.id_checkBoxUnderCheckBox$uniqueUserId, checkBoxUnderCheckBox$uniqueUserId.name, checkBoxUnderCheckBox$uniqueUserId.state
                          FROM checkBox$uniqueUserId
                          LEFT JOIN
                          checkBoxUnderCheckBox$uniqueUserId ON checkBox$uniqueUserId.id_checkBoxUnderCheckBox$uniqueUserId = 
                          checkBoxUnderCheckBox$uniqueUserId.id_checkBoxUnderCheckBox$uniqueUserId
                           WHERE checkBox$uniqueUserId.name = '". $row['name']. "'";
                $resultUnderCheckBox = $link->query($oneMoreQuery);
                echo '<div class="underCheckBoxes">';

                while ($rowUnderCheckBox = $resultUnderCheckBox->fetch_assoc()) {
                    if ($rowUnderCheckBox['name'] != NULL) {
                        $underCheckBoxId = "id_checkBoxUnderCheckBox$uniqueUserId";
                        $checked = $rowUnderCheckBox['state'] == 1;
                        echo '<input type="checkbox" onclick="updateRecord('. "'checkBoxUnderCheckBox$uniqueUserId', $rowUnderCheckBox[$underCheckBoxId]" .
                            ')" '; if ($checked) {echo 'checked';}
                        echo '>'. $rowUnderCheckBox['name']. '<br>';
                    }
                }
                echo '</div>';
                echo '</div>' . '</div><br>';
                array_push($checkBoxNames, $row['name']);
            }
        }
    }
    echo '</div>';
}