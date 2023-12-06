<?php
    session_start();
    require 'BackEnd/conecta.php';
    $con = conecta();
    $itemId = $_REQUEST['id'];
    $itemAmount = $_REQUEST['cantidad'];
    $clientID = $_SESSION['username'];
    $flagForOrders = 0;
    $existingOrderId = '';
    $orderExisting = "SELECT * FROM pedidos WHERE status = 1 AND client_id = '$clientID' ";
    $OrdeRes = $con->query($orderExisting);
    if( $OrdeRes->num_rows >= 1){ // He has an order created we have to check if its the same order so we can just add 1 to the amount or its a diferente item
        $flagForOrders = 3; //our response its 2 because our client has an active order but we dont know if its the same product
        while($row = $OrdeRes->fetch_array()) {
            $existingOrderId = $row["id"];
            $itemOrderSeach = "SELECT * FROM items_orders WHERE order_id = '$existingOrderId' AND product_id = '$itemId'";
            $item_ordersRes = $con->query($itemOrderSeach);
            if($item_ordersRes->num_rows >= 1){
                while($row = $item_ordersRes->fetch_array()) {
                    $existingAmount = $row["amount"];
                    $newAmount = $existingAmount + $itemAmount;
                    $oneMoreItemSQL = "UPDATE items_orders SET amount='$newAmount' WHERE order_id = '$existingOrderId' AND product_id = '$itemId' ";
                    $res = $con->query($oneMoreItemSQL);
                }
                $flagForOrders = 2; // Our flag is 2 because our client has an active order and the order its the same product he justa added more

            }
        }

    }
    if($flagForOrders == 3 or $flagForOrders == 0){ //He dosent have any order so we can create a new one
        $flagForOrders = 1;
        $newOrderSQL = "INSERT INTO pedidos (client_id) VALUES ('$clientID')";
        $res = $con->query($newOrderSQL);
        $getFromOrder = "SELECT * FROM pedidos WHERE client_id = '$clientID' AND status = 1";
        $res = $con->query($getFromOrder);
        $orderId = '';
        $orderDate = '';
        $orderStatus = '';
        while($row = $res->fetch_array()) {
            $orderId = $row["id"];
            $orderDate = $row["date"];
            $orderStatus = $row["status"];
        }
        $productQuery = "SELECT * FROM Products WHERE id = '$itemId'";
        $res = $con->query($productQuery);
        $itemPrice='';
        while($row = $res->fetch_array()) {
            $itemPrice = $row["product_cost"];
        }
        $newItemOrders = "INSERT INTO items_orders (order_id,product_id,amount,price) VALUES ('$orderId','$itemId','$itemAmount','$itemPrice') ";
        $res = $con->query($newItemOrders);

    }




    echo 'bandera= '.$flagForOrders;




?>
