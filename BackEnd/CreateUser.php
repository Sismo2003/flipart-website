<?php
    require 'conecta.php';
    $con = conecta();
    $userName = $_REQUEST['userName'];
    $fullName = $_REQUEST['fullName'];
    $email = $_REQUEST['email'];
    $pass = $_REQUEST['pass'];
    $phoneNumber = $_REQUEST['phone'];
    $birthday = $_REQUEST['birthday'];
    $pass_enc = md5($pass);

    $newUser = "INSERT INTO Users (userName,fullName,email,pass,phone,birthday) VALUES ('$userName','$fullName','$email','$pass_enc','$phoneNumber','$birthday') ";
    $res = $con->query($newUser);
    echo 1;

?>
