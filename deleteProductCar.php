<?php
session_start();
require 'BackEnd/conecta.php';
$con = conecta();
$orderId = $_REQUEST['order'];
$q = "UPDATE pedidos SET status = 0 WHERE id = '$orderId' AND status = 1";
$ans = $con->query($q);
echo $ans->num_rows;