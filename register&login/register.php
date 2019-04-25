<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>注册</title>
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
 * Date: 2019-04-23
 * Time: 15:59
 * Func: 实现注册功能
 */

session_start();
require 'function.php';

// 判断是否能够满足注册条件
$isCanRegister = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isCanRegister = true;
    // 检测用户名是否能够使用
    $userNameError = isUserNameLegal(trim($_POST['userName']));
    if ($userNameError == '') {
        $userName = trim($_POST['userName']);
    } else {
        $isCanRegister = false;
    }
    if (isUserNameExist($userName) == true) {
        $userNameError = '*该用户名已被注册，请重新输入';
        $isCanRegister = false;
    }
    // 检测注册邮箱是否能够使用
    $emailError = isemailLegal(trim($_POST['email']));
    if ($emailError == '') {
        $email = trim($_POST['email']);
    } else {
        $isCanRegister = false;
    }
    if (isEmailExist($email) == true) {
        $emailError    = '*该邮箱已被占用，请重新输入';
        $isCanRegister = false;
    }
    // 检测密码是否能够使用
    $passwordError = isPasswordLegal(trim($_POST['password']));
    if ($passwordError == '') {
        $password = md5($_POST['password']);
    } else {
        $isCanRegister = false;
    }
    // 检测两次密码输入是否一致
    $passwordConfirmError = isPasswordSame(trim($_POST['password']), trim($_POST['passwordConfirm']));
    if ($passwordConfirmError != '') {
        $isCanRegister = false;
    }
    // 检测用户是否正确进行了性别选择
    $genderError = isGenderLegal(trim($_POST['gender']));
    if ($genderError == '') {
        $gender = trim($_POST['gender']);
    } else {
        $isCanRegister = false;
    }
    // 检测用户是否正确输入了验证码
    $checkCodeError = isCheckCodeCorrect(trim($_POST['checkCode']));
    if ($checkCodeError != '') {
        $isCanRegister = false;
    }
    // 读取用户选择的密保问题
    $question = trim($_POST['question']);
    // 检测用户是否输入了密保问题答案
    $answerError = isAnswerLegal(trim($_POST['answer']));
    if ($answerError == '') {
        $answer = trim($_POST['answer']);
    } else {
        $isCanRegister = false;
    }
    // 检测用户两次输入的密保问题答案是否一致
    $answerConfirmError = isAnswerSame(trim($_POST['answer']), trim($_POST['answerConfirm']));
    if ($answerConfirmError != '') {
        $isCanRegister == false;
    }
}

// 若满足注册条件，则将注册信息插入到数据库的用户表中
if ($isCanRegister == true) {
    $db = new mysql();
    $db->db_select('db_bookstore');
    $sql    = "insert into t_user (`user_name`,`email`,`password`,`gender`,`register_time`,`question`,`answer`)
    values ('$userName','$email','$password','$gender',curdate(),'$question','$answer')";
    $result = $db->query($sql);
    if ($result == true) {
        $isRegisterSuccess = true;
    }
}

?>

<form method="POST" action="<? echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    用户昵称：<input type="text" name="userName"/>
    <? echo "<span class='error'>" . $userNameError . "</span>"; ?> <br>
    常用邮箱：<input type="text" name="email"/>
    <? echo "<span class='error'>" . $emailError . "</span>"; ?> <br>
    注册密码：<input type="password" name="password"/>
    <? echo "<span class='error'>" . $passwordError . "</span>"; ?> <br>
    确认密码：<input type="password" name="passwordConfirm"/>
    <? echo "<span class='error'>" . $passwordConfirmError . "</span>"; ?> <br>
    选择性别：<input type="radio" name="gender" value="男"/>男
    <input type="radio" name="gender" value="女"/>女
    &emsp;&emsp;&emsp;&emsp;&emsp;
    <? echo "<span class='error'>" . $genderError . "</span>"; ?> <br>
    <label>密保问题：</label>
    <select name="question">
        <?php
        html_register_question();
        ?>
    </select> <br>
    问题答案：<input type="text" name="answer"/>
    <? echo "<span class='error'>" . $answerError . "</span>"; ?> <br>
    确认答案：<input type="text" name="answerConfirm">
    <? echo "<span class='error'>" . $answerConfirmError . "</span>"; ?> <br>
    验证码&emsp;：<input type="text" size="5" name="checkCode"/>
    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;
    <? echo "<span class='error'>" . $checkCodeError . "</span>"; ?> <br>
    <img src="check_code.php"/> <br>
    <input type="submit" value="注册"/>
</form>

</body>
</html>

<?php

// 若注册数据插入数据库成功，则显示注册成功提示
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $isRegisterSuccess == true) {
    echo "<span class='success'>" . '注册成功！' . "</span>";
}

?>