<?php
/**
 * Created by PhpStorm.
 * User: eeatem
 * Date: 2019-04-23
 * Time: 16:00
 * Func: 制作一个PHP+MySQL的类
 */

require 'config.php';

class mysql
{

    // 定义私有属性变量
    private $host;
    private $user;
    private $password;
    private $charset;
    // 定义数据库连接语句
    public $conn;

    // 初始化变量的方法
    function __construct()
    {
        $this->host     = DB_HOST;
        $this->user     = DB_USER;
        $this->password = DB_PASSWORD;
        $this->charset  = DB_CHARSET;
        $this->connect();
    }

    // 连接数据库的方法
    function connect()
    {
        // 执行MySQL连接语句
        $this->conn = mysqli_connect($this->host, $this->user, $this->password) or die (mysqli_error());
        // if($this->conn) echo '数据库连接成功！';
        mysqli_set_charset($this->conn, DB_CHARSET);
    }

    // 切换数据库的方法
    function db_select($dbName)
    {
        // 切换数据库语句
        mysqli_select_db($this->conn, $dbName);
    }

    // 执行SQL语句的方法
    function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

    // 遍历SQL查询结果的方法
    function fetch($result)
    {
        $row = mysqli_fetch_assoc($result);
        return $row;
    }

}

/*
    // 测试数据库连接和选择数据库
    $db = new mysql();
    $db->db_select('db_test');

    // 测试执行SQL语句
    $sql = "insert into t_test (`user_name`) values ('Amy')";
    $db->query($sql);

    // 测试取出字段内容
    $sql    = "select * from t_test";
    $result = $db->query($sql);
    while ($row = $db->fetch($result)) {
        echo $row['user_name'];
    }
*/

?>