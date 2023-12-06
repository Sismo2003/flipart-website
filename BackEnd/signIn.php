<?php
    require 'conecta.php';
    $con = conecta();
    $user = $_REQUEST['user'];
    $pass = $_REQUEST['pass'];
    $enc_pass =md5($pass);


    $sql = "SELECT * FROM Users where userName = '$user' AND  pass = '$enc_pass' AND status = 1 AND deleted = 0";
    $res = $con->query($sql);
    if($res->num_rows == 1){
        session_start();
        $_SESSION["logged"] = true;
        $_SESSION["username"] = $user;
    }
    echo $res->num_rows;


?>