<!DOCTYPE html>
<html>
<head>
    <title>登陆</title>
    <meta charset="UTF-8"/>
    <style>
        .error {
            color: red;
        }

        .success {
            color: green;
        }

        .tips {
            color: blue;
        }
    </style>
</head>
<body>

<?php
/**
 * Created by PhpStorm.
 * User: eeatem
 * Date: 2019-04-24
 * Time: 13:13
 * Func: 实现登陆功能
 */

session_start();
require 'function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 检测登陆页面用户名或邮箱输入是否能够使用
    $userNameError = isUserNameEffective($_POST['userName']);
    if ($userNameError == '') {
        $userName = gainUserName(trim($_POST['userName']));
    }
    // 检测登陆页面密码输入是否正确
    $passwordError = isPasswordCorrect($userName, $_POST['password']);
    if ($passwordError == '') {
        $isLoginSuccess = true;
    } else {
        $isLoginSuccess = false;
    }
    // 检测登陆页面验证码输入是否正确
    $checkCodeError = isCheckCodeCorrect($_POST['checkCode']);
    if ($isLoginSuccess == true) {
        if ($checkCodeError == '') {
            $isLoginSuccess = true;
        } else {
            $isLoginSuccess = false;
        }
    }
    // 检测用户选择的登陆权限是否与之匹配
    if ($isLoginSuccess == true) {
        $levelError = isLevelLegal($userName, trim($_POST['level']));
        if ($levelError == '') {
            $isLoginSuccess = true;
        } else {
            $isLoginSuccess = false;
        }
    }
}

?>

<form method="POST" action="<? echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    用户名或邮箱：<input type="text" name="userName"/>
    <? echo "<span class='error'>" . $userNameError . "</span>" ?> <br>
    登陆密码&emsp;&emsp;：<input type="password" name="password"/>
    <? echo "<span class='error'>" . $passwordError . "</span>" ?> <br>
    <label>选择权限&emsp;&emsp;：</label>
    <select name="level">
        <? html_login_level(); ?>
    </select>
    &emsp;&emsp;&emsp;&emsp;<? echo "<span class='error'>" . $levelError . "</span>"; ?> <br>
    验证码&emsp;&emsp;&emsp;：<input type="text" name="checkCode"/>
    <? echo "<span class='error'>" . $checkCodeError . "</span>" ?> <br>
    <img src="check_code.php"/> <br>
    <input type="submit" value="登陆"/>
</form>

</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" && $isLoginSuccess == true) {
    echo "登陆成功，欢迎您，用户：$userName !";
    // 登陆成功后，把用户名存入session中
    $_SESSION['userName']=$userName;
}

?>