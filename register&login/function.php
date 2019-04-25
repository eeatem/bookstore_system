<?php
/**
 * Created by PhpStorm.
 * User: eeatem
 * Date: 2019-04-23
 * Time: 16:37
 * Func: 储存自定义函数
 */

session_start();
require 'mysql_class.php';

/*
 *
 * <--------------------------------------注册页面-------------------------------------->
 *
 */

// 检测用户名是否合法
function isUserNameLegal($userName)
{
    if (empty($userName)) {
        $userNameError = '*请输入用户名';
    } else if (!preg_match("/^[a-zA-Z][a-zA-Z0-9_]{3,9}$/", $userName)) {
        $userNameError = '*用户名必须以字母开头，仅包含字母、数字、下划线，限制长度4-10位';
    } else {
        $userNameError = '';
    }

    return $userNameError;
}

// 检测用户名是否已被注册
function isUserNameExist($userName)
{
    // 查询数据库的用户表中是否已经存在该用户名
    $db = new mysql();
    $db->db_select('db_bookstore');
    $sql    = "select * from t_user where `user_name`='$userName'";
    $result = $db->query($sql);
    $row    = $db->fetch($result);
    if ($row == true) {
        return true;
    } else {
        return false;
    }
}

// 检测注册邮箱是否合法
function isEmailLegal($email)
{
    if (empty($email)) {
        $emailError = '*请输入常用邮箱';
    } else if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
        $emailError = '*邮箱格式错误，请输入正确的邮箱地址';
    } else {
        $emailError = '';
    }

    return $emailError;
}

// 检测邮箱是否已被占用
function isEmailExist($email)
{
    // 查询数据库的用户表中是否已存在该邮箱
    $db = new mysql();
    $db->db_select('db_bookstore');
    $sql    = "select * from t_user where `email`='$email'";
    $result = $db->query($sql);
    $row    = $db->fetch($result);
    if ($row == true) {
        return true;
    } else {
        return false;
    }
}

// 检测密码是否合法
function isPasswordLegal($password)
{
    if (empty($password)) {
        $passwordError = '*请输入密码';
    } else if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,14}$/", $password)) {
        $passwordError = '*密码必须包含大小写字母和数字，不能使用特殊字符，限制长度6-14位';
    } else {
        $passwordError = '';
    }

    return $passwordError;
}

// 检测两次密码输入是否一致
function isPasswordSame($password, $passwordConfirm)
{
    if ($passwordConfirm != $password) {
        $passwordSameError = '*两次密码输入不一致，请重新输入';
    } else {
        $passwordSameError = '';
    }

    return $passwordSameError;
}

// 检测用户是否正确选择了性别
function isGenderLegal($gender)
{
    if ($gender != '男' && $gender != '女') {
        $genderError = '*请选择性别';
    } else {
        $genderError = '';
    }

    return $genderError;
}

// 检测用户是否正确输入了验证码
function isCheckCodeCorrect($checkCode)
{
    if ($checkCode != $_SESSION['checkCode']) {
        $checkCodeError = '*请输入正确的验证码';
    } else {
        $checkCodeError = '';
    }

    return $checkCodeError;
}

// 配置html注册页面中的密保问题
function html_register_question()
{
    $questions = array('您的第一个配偶是？', '您最喜欢的歌手是？', '您的第一位老师是？');

    foreach ($questions as $value) {
        echo "<option value='$value'>$value</option>";
    }
}

// 检测用户是否输入了密保问题答案
function isAnswerLegal($answer)
{
    if ($answer == '') {
        $answerError = '*请输入密码问题答案';
    }
    // 限制密保问题答案输入规则（待完善）
    /* else if (!preg_match("", $answer)) {
        $answerError = '*仅包含汉字，且最多输入10个汉字';
    }
    */ else {
        $answerError = '';
    }

    return $answerError;
}

// 检测用户两次输入的密保问题答案是否一致
function isAnswerSame($answer, $answerConfirm)
{
    if ($answerConfirm != $answer) {
        $answerSameError = '*两次答案输入不一致，请重新输入';
    } else {
        $answerSameError = '';
    }

    return $answerSameError;
}

/*
 *
 * <--------------------------------------登陆页面-------------------------------------->
 *
 */

// 配置html登陆页面中的用户权限选择
function html_login_level()
{
    $levels = array('普通用户', '商家', '管理员');
    $i      = 0;
    foreach ($levels as $value) {
        echo "<option value='$i'>$value</option>";
        ++$i;
    }
}

// 检测登陆页面用户名或邮箱输入是否能够使用
function isUserNameEffective($userName)
{
    // 查询数据库是否存在登陆页面输入的用户名或邮箱
    $db = new mysql();
    $db->db_select('db_bookstore');
    // 查询数据库是否存在登陆页面输入的用户名
    $sql    = "select * from t_user where `user_name`='$userName'";
    $result = $db->query($sql);
    $row1   = $db->fetch($result);
    // 查询数据库是否存在登陆页面输入的邮箱
    $sql    = "select * from t_user where `email`='$userName'";
    $result = $db->query($sql);
    $row2   = $db->fetch($result);
    // 检测用户名是否为空
    if ($userName == '') {
        $userNameError = '*请输入用户名或邮箱';
    } else if (preg_match('/\s/', $userName)) {
        $userNameError = '*请勿输入空格';
    } else
        if ($row1 == false && $row2 == false) {
            $userNameError = "*该用户名或邮箱不存在 " . "<a href='register&login/register.php'>注册</a>";
        } /*
      else if (!preg_match("/^[A-Za-z0-9]+$/", $userName)) {
        $userNameError = '*请勿输入汉字、空格或其他特殊字符';
    }
    */
        else {
            $userNameError = '';
        }

    return $userNameError;
}

// 获取登陆页面输入的用户名（根据用户名或邮箱）
function gainUserName($userName)
{
    // 查询数据库是否存在登陆页面输入的用户名或邮箱
    $db = new mysql();
    $db->db_select('db_bookstore');
    // 查询数据库是否存在登陆页面输入的用户名
    $sql    = "select * from t_user where `user_name`='$userName'";
    $result = $db->query($sql);
    $row1   = $db->fetch($result);
    // 查询数据库是否存在登陆页面输入的邮箱
    $sql    = "select * from t_user where `email`='$userName'";
    $result = $db->query($sql);
    $row2   = $db->fetch($result);
    if ($row1 == true) {
        $userName = $row1['user_name'];
    } else {
        $userName = $row2['user_name'];
    }

    return $userName;
}

// 检测登陆页面密码输入是否正确
function isPasswordCorrect($userName, $password)
{
    // 查询数据库中用户名或邮箱是否与输入的密码匹配
    $db = new mysql();
    $db->db_select('db_bookstore');
    $sql    = "select * from t_user where `user_name`='$userName'";
    $result = $db->query($sql);
    $row    = $db->fetch($result);
    // 检测密码是否为空
    if ($password == '') {
        $passwordError = '*请输入密码';
    } else if (md5($password) != $row['password']) {
        $passwordError = '*密码错误，请重新输入';
    } else {
        $passwordError = '';
    }

    return $passwordError;
}

// 检测登陆页面用户选择权限是否与之匹配
function isLevelLegal($userName, $level)
{
    // 查询数据库中用户的权限（等级）
    $db = new mysql();
    $db->db_select('db_bookstore');
    $sql    = "select * from t_user where `user_name`='$userName'";
    $result = $db->query($sql);
    $row    = $db->fetch($result);
    if ($level == '2') {
        if($level != $row['level']) {
            $levelError = '*请选择正确的登陆权限';
        }
    } else if ($level < $row['level']) {
        $levelError = '*请选择正确的登陆权限';
    } else {
        $levelError = '';
    }

    return $levelError;
}