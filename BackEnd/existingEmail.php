<?php
require "conecta.php";
$con = conecta();
$email = $_REQUEST['correo'];

$sql = "SELECT * FROM Users WHERE status = 1 AND deleted = 0 AND email = '$email'";
$res = $con->query($sql);
echo $res->num_rows
?>

