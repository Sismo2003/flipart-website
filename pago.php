
<?php
session_start();
require "BackEnd/conecta.php";


$isLoggedIn = isset($_SESSION['username']);
$currentUsername = $_SESSION['username'];

if ($isLoggedIn && isset($_POST['logout'])) {

    $_SESSION = array();


    session_destroy();


    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlipArt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/flatly/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="Styles/footer.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="index.php">FlipART</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="products.php">Productos</a>
            </li>
            <?php
            if ($isLoggedIn) {
                $con = conecta();
                $usuario = $_SESSION['username'];
                $sql = "SELECT * FROM pedidos WHERE status = 1 AND client_id = '$usuario'";
                $res = $con->query($sql);
                echo '
                           <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#cartModal">
                                    <i class="fas fa-shopping-cart"></i> Carrito
                                    <span class="badge badge-pill badge-danger">0</span>
                                </a>
                            </li>
                    ';
            }
            else{
                echo '
                           <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#cartModal">
                                    <i class="fas fa-shopping-cart"></i> Carrito
                                    <span class="badge badge-pill badge-danger"> 0 </span>
                                </a>
                            </li>
                    ';
            }


            ?>
            <li class="nav-item">
                <a class="nav-link" href="contact.php">Contactanos</a>
            </li>
            <?php
            if ($isLoggedIn) {
                echo '<li class="nav-item"><a class="nav-link" href="profile.php">' . $_SESSION['username'] . '</a></li>';
                echo '<form method="post" class="nav-item"><button type="submit" name="logout" class="btn btn-link nav-link">Logout</button></form>';
            } else {
                echo '<li class="nav-item"><a class="nav-link" href="login.php">Login/Sign-up</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>








<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                <h2>FlipART</h2><h3 class="pull-right">orden # 12345</h3>
                <h6><strong>FlipArt</strong> agradece tu compra, seguiremos rompiendola para darte el mejor servicio. <STRONG>#ARTEURBANO</STRONG></h6>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <?php

                    $User = "SELECT * FROM Users WHERE userName = '$currentUsername' AND status = 1 AND deleted = 0";
                    $res = $con->query($User);
                    while($row = $res->fetch_array()) {
                    $fullName = $row['fullName'];
                    $email = $row['email'];
                    $phoneNumber = $row['phone'];
                    $birthday = $row['birthday'];

                    ?>
                    <address>
                        <strong>Cargo:</strong><br>
                        <?php echo $fullName?><br>
                        #1421 Blvd. Gral. Marcelino García Barragán<br>
                        Col. Olimpica<br>
                        44430 Guadalajara, Jal.
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>Enviado:</strong><br>
                        #976 Av. Juárez<br>
                        Col Americana,<br>
                        44100 Guadalajara, Jal.
                    </address>
                </div>
            </div>
            <div class="row"> <br>
                <div class="col-xs-6">
                    <address>
                        <strong>Payment Method:</strong><br>
                        Visa Terminacion **** 4242<br>
                        <?php echo $email ?>
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>Fecha de orden:</strong><br>

                        <?php echo date('Y-m-d ');?> <br><br>
                    </address>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
<?PHP ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Tu order</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <td><strong>Item</strong></td>
                                <td class="text-center"><strong>Precio</strong></td>
                                <td class="text-center"><strong>Cant.</strong></td>
                                <td class="text-right"><strong>Total</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                            <?php
                            $con = conecta();
                            $usuario = $_SESSION['username'];
                            $sql = "SELECT * FROM pedidos WHERE status = 1 AND client_id = '$usuario'";
                            $res = $con->query($sql);
                            $total = 0;
                            while($row = $res->fetch_array()) {
                                $userOrderId = $row["id"];
                                $productoQuery = "SELECT * FROM items_orders WHERE order_id = '$userOrderId'";
                                $productoANS = $con->query($productoQuery);
                                while($productrow = $productoANS->fetch_array()) {
                                    $invoiceNumber = $productrow['id'];
                                    $productID = $productrow['product_id'];
                                    $productAmount = $productrow['amount'];
                                    $productPrice = $productrow['price'];
                                    //Obten Detalles del Producto
                                    $tablaDeProductos = "SELECT * FROM Products WHERE id = '$productID' AND deleted = 0";
                                    $tablaDeProductosANS = $con->query($tablaDeProductos);
                                    while($tablaDeProductosROW = $tablaDeProductosANS->fetch_array()) {
                                        $itemName = $tablaDeProductosROW['product_name'];
                                        $itemCode = $tablaDeProductosROW['product_code'];
                                        $itemDescr = $tablaDeProductosROW['product_description'];
                                        $itemStock = $tablaDeProductosROW['product_stock'];
                                        $file = $tablaDeProductosROW["archivo"];
                                        $finalFile = substr($file, 3);
                                        $total += ($productPrice * $productAmount);
                            ?>
                            <tr>
                                <td><?php echo $itemName ?></td>
                                <td class="text-center">$<?php echo $productPrice ?>.00</td>
                                <td class="text-center"><?php echo $productAmount ?></td>
                                <td class="text-right">$<?php $quantityPrice = $productPrice * $productAmount; echo $quantityPrice; $total+=$quantityPrice;?>.00</td>
                            </tr>
                            <?php }}}?>
                            <tr>
                                <td class="thick-line"></td>
                                <td class="thick-line"></td>
                                <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                <td class="thick-line text-right">$<?php echo $total?>.00</td>
                            </tr>
                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line text-center"><strong>Envio</strong></td>
                                <td class="no-line text-right">$0 </td>
                            </tr>
                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line text-center"   ><strong>I.V.A</strong> <h14 style="font-size: 10px">0.16%</h14></td>
                                <td class="no-line text-right">$<?php $iva = $total* 0.16;$total+=$iva ;echo $iva?> </td>
                            </tr>
                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line text-center"><strong>Total</strong></td>
                                <td class="no-line text-right"><?php  echo $total?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>























<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Carrito de Compras</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="media">
                    <div class="media-body">
                        <h5 class="mt-0">Acualmente no tienes productos en el carrito</h5>
                        <div id="productDescription">Empieza a agregar productos aqui <strong>HOY MISMO</strong>!</div>
                    </div>
                </div>
                <hr>
                <div>
                    <strong>Total:</strong>
                    <span id="totalPrice">$0.00</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Realizar Compra</button>
            </div>
        </div>
    </div>
</div>


<footer class="bg-dark text-white text-center py-3">
    <div class="container">
        <p>&copy; 2023 FlipArt Todos los derechos reservados.</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="Js/carrito.js"></script>
<script src="Js/logIn.js"></script>
<?php
    $con = conecta();
    $usuario = $_SESSION['username'];
    $sql = "UPDATE pedidos SET status = 0 WHERE client_id = '$usuario'";
    $res = $con->query($sql);

?>
</body>
</html>