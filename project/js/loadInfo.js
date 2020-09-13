function LoadInfo(vkId)
{
    $.ajax({
        async: true,
        type: 'GET',
        dataType: 'text',
        url: 'loadUserInfo.php',
        data: {userId: vkId},
    });
}
