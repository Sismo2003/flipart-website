<?php
    session_start();
    require 'BackEnd/conecta.php';
    $con =conecta();
    $username = $_SESSION['username'];
    $fullName = $_REQUEST['name'];
    $phone = $_REQUEST['phone'];
    $email = $_REQUEST['email'];
    $sql = "SELECT * FROM Users WHERE userName = '$username' AND deleted = 0 AND status = 1";
    $res = $con->query($sql);
    $madedChanges = 0;
    while($row = $res->fetch_array()){
        if($fullName != $row['fullName']){
            $q = "UPDATE Users SET fullName = '$fullName' WHERE userName = '$username' AND deleted = 0 AND status = 1";
            $ans = $con->query($q);
            $madedChanges++;
        }
        if($fullName != $row['email']){
            $q = "UPDATE Users SET email = '$email' WHERE userName = '$username' AND deleted = 0 AND status = 1";
            $ans = $con->query($q);
            $madedChanges++;
        }
        if($fullName != $row['phone']){
            $q = "UPDATE Users SET phone = '$phone' WHERE userName = '$username' AND deleted = 0 AND status = 1";
            $ans = $con->query($q);
            $madedChanges++;
        }
    }

    echo $madedChanges;