<?php

$client_id = 7590782; // ID приложения
$client_secret = 'QJ1vOsIifXsYyC7Jg87z'; // Защищённый ключ
$redirect_uri = 'http://localhost/auth.php'; // Адрес сайта

$url = 'http://oauth.vk.com/authorize'; // Ссылка для авторизации на стороне ВК

$params = [ 'client_id' => $client_id, 'redirect_uri'  => $redirect_uri, 'response_type' => 'code']; // Массив данных, который нужно передать для ВК содержит ИД приложения код, ссылку для редиректа и запрос code для дальнейшей авторизации токеном

echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Авторизация через ВК</a></p>';
echo '<p class="helloMessage">Чтобы пользоваться функционалом данного проекта, необходимо авторизоваться</p>';

if (isset($_GET['code'])) {
    session_start();
    $result = true;
    $params = [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $_GET['code'],
        'redirect_uri' => $redirect_uri
    ];

    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

    if (isset($token['access_token'])) {
        $params = [
            'uids' => $token['user_id'],
            'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo',
            'access_token' => $token['access_token'],
            'v' => '5.101'];

        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        if (isset($userInfo['response'][0]['id'])) {
            $userInfo = $userInfo['response'][0];
            $result = true;
        }
    }

    $_SESSION['userInfo'] = $userInfo;
    header('Location: index.php');
}