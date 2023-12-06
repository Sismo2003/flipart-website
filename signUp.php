<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $phone = $_POST['phone'];
    $birth_date = $_POST['birthdate'];



    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlipART</title>
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
                                    <span class="badge badge-pill badge-danger">';echo  $res->num_rows; echo '</span>
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

<div class="container mt-5">
    <h2>Registro</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="fullname">Nombre Completo:</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input onblur="verifyEmail()" type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="alert alert-dismissible alert-danger emailWrong" id="emailWrong" style="display: none">
        </div>
        <div class="form-group">
            <label for="pwd">Contraseña:</label>
            <input type="password" class="form-control" id="pwd" name="pwd" required>
        </div>
        <div class="form-group">
            <label for="phone">Teléfono:</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="birthdate">Fecha de Nacimiento:</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate" required>
        </div>
        <button type="submit" name="submit" onclick="CreateUser()" class="btn btn-primary">Registrarse</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="login.php">INICIA SESION AQUÍ</a></p>
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
            <?php

            if($isLoggedIn){
                $con = conecta();
                $usuario = $_SESSION['username'];
                $sql = "SELECT * FROM pedidos WHERE status = 1 AND client_id = '$usuario'";
                $res = $con->query($sql);

                $total = 0;
                $numFilas=$res->num_rows;
                if ($numFilas=='0'){
                    echo'
                               <div class="modal-body">
                <div class="media">
                    <div class="media-body">
                        <h5 class="mt-0">Acualmente no tienes productos en el carrito</h5>
                        <div id="productDescription">Empieza a agregar productos aqui <strong>HOY MISMO</strong>!</div>
                    </div>
                </div>
                <hr>
            </div>
                    ';
                }else{


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
                                echo '
                <div class="modal-body">
                    <div class="media">
                        <img src="';echo $finalFile; echo '" style="max-width: 64px;height: 64px;object-fit: cover;" class="mr-3" alt="Producto">
                        <div class="media-body">
                            <h5 class="mt-0">';echo $itemName; echo '</h5>
                            <div id="productDescription">';echo $itemDescr; echo'</div>
                            <div id="productPrice"><strong>Precio:</strong> $';echo $productPrice;echo '.00 Unidad</div>
                            <div class="input-group mt-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" id="subtractQuantity">-</button>
                                </div>
                                <input type="text" class="form-control text-center" value=" ';echo $productAmount;echo '" id="quantity" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="addQuantity">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                ';}}}} }
            else{
                echo '
                <div class="modal-body">
                    <div class="media">
                        <div class="media-body">
                            <h5 class="mt-0"><strong>Deberas Iniciar Sesion para continuar</strong></h5>
                            <div id="productDescription">Si no te registras hermano o te loggeas no puedes hacer pedidos</div>
                            <div id="productPrice"><strong>Precio:</strong> $00.00 Unidad</div>
                            <div class="input-group mt-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" id="subtractQuantity">-</button>
                                </div>
                                <input type="text" class="form-control text-center" value="0" id="quantity" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="addQuantity">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>';
            }
            ?>
            <div>
                <strong>Total:</strong>
                <span id="totalPrice">$<?php  if($isLoggedIn)  echo $total; else echo 00?>.00</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="sale();return false">Realizar Compra</button>
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
<script>
    function sale(){

        if(<?php echo  $numFilas?> != 0){
            window.location.href='pago.php';
        }


    }

</script>
</body>

</html>