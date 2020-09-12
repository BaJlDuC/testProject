<html>
<head>
<title>Главная</title>
<h2 class="main">Главная</h2>
    <link rel="stylesheet" href="css/style.css" media="all">
        </head>
<body>
<?php
require 'dbFunctions.php';
session_start();

if (isset($_SESSION['userInfo']))
{
    echo '<div class="account"><div class="photo"><img src="' . $_SESSION['userInfo']['photo'] . '"/></div>';
    echo '<div class="userName"><p>' . $_SESSION['userInfo']['first_name'] . '</p></div>' .
        '<p class="logout"><a href="http://localhost?out">Выход</a></p></div>';
    if (isset($_GET['out']))
    {
        session_unset();
        header('Refresh: 0');
    }
    else {
        //-------здесь возможно нужна проверка существования пользователя в бд
        $userIdFromVk = $_SESSION['userInfo']['id'];

        if (IsUserExistInDataBase($userIdFromVk))
        {
            echo 'exist, updating data from user...';
        }
        else
        {
            echo 'does not exist, adding user in db...';
            AddUserInDataBase($userIdFromVk);
        }
        require_once 'content.php';
    }
}
else {
    require_once 'auth.php';
}
?>
</body>
</html>
