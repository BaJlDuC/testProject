<html>
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/loadInfo.js"></script>
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
        echo '<script type="text/javascript">LoadInfo(' . $userIdFromVk . ');</script>';

        require_once 'content.php';
    }
}
else {
    require_once 'auth.php';
}
?>
</body>
</html>
