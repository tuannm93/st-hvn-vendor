$(document).ready(function () {

    $('#user_id').focus();
    var checkValue = getCookie('orange_remember_check');
    if (checkValue == 'checked') {
        document.getElementById("user_id").value = getCookie('orange_remember_id');
        document.getElementById("password").value = getCookie('orange_remember_pass');
        document.getElementById("rememberLogin").checked = true;
    }
});

function submitbtn() {
    if (document.getElementById("rememberLogin").checked == true) {
        setCookie('orange_remember_id', document.getElementById("user_id").value, 30);
        setCookie('orange_remember_pass', document.getElementById("password").value, 30);
        setCookie('orange_remember_check', 'checked', 30);
    } else {
        deleteCookie('orange_remember_id');
        deleteCookie('orange_remember_pass');
        deleteCookie('orange_remember_check');
    }
}

function getCookie($cookieName) {
    var $cookies = document.cookie.split(';');
    for (var $i = 0; $i < $cookies.length; $i++) {
        var $cookie = $cookies[$i].trim().split('=');
        if ($cookie[0] == $cookieName) {
            return $cookie[1];
        }
    }
    return "";
}

function setCookie($cookieName, $cookieValue, $days) {
    var $dateObject = new Date();
    $dateObject.setTime($dateObject.getTime() + ($days*24*60*60*1000));
    var $expires = "expires=" + $dateObject.toGMTString();
    document.cookie = $cookieName + "=" + $cookieValue + "; " + $expires;
}

function deleteCookie($cookieName) {
    document.cookie = $cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
}