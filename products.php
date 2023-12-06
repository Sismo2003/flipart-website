
<?php
require 'BackEnd/conecta.php';
session_start();



$isLoggedIn = isset($_SESSION['username']);
if($isLoggedIn){
    $con = conecta();
    $usuario = $_SESSION['username'];
    $sql = "SELECT * FROM pedidos WHERE status = 1 AND client_id = '$usuario'";
    $res = $con->query($sql);
    $numFilas = 0;
    $total = 0;
    $numFilas=$res->num_rows;
}else{
    $numFilas = 0;
    $total = 0;
}

if ($isLoggedIn && isset($_POST['logout'])) {
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




    <div class="container my-5">
        <h1>Productos en venta</h1>
        <br>
        <p>Envios gratis a todo mexico!</p>
        <div class="row">

            <?php
            $con = conecta();


            $sql = "SELECT * FROM Products
                                    WHERE status = 1 AND deleted = 0";
            $res = $con->query($sql);
            while ($row = $res->fetch_array()) {
            $id = $row["id"];
            $productName = $row["product_name"];
            $productCode = $row["product_code"];
            $productDescription = $row["product_description"];
            $productCost = $row["product_cost"];
            $productStock = $row["product_stock"];
            $file = $row["archivo"];
            $finalFile = substr($file, 3);

            ?>
            <div class="card" style="width: 18rem; margin: 1%"  >
                <img class="card-img-top" src="<?php echo $finalFile?>" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $productName ?></h5>
                    <p class="card-text"><?php echo $productDescription?></p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Precio:</strong> $<?php echo $productCost?>.00</li>
                    <li class="list-group-item"> <strong>Disponibilidad:</strong>  <?php echo $productStock?></li>
                    <li class="list-group-item"> <strong>Codigo:</strong>  <?php echo $productCode?></li>
                </ul>
                <div class="card-body">
                    <label for="botonespia_<?php echo $id; ?>" class="labelfor"><a  class="card-link">Agregar al Carrito!</a></label>
                    <button onclick="addToCard('<?php echo $id; ?>')" class="hidebotton" id="botonespia_<?php echo $id; ?>"></button>
                    <div class="form-group">

                        <select class="form-control" id="cantidad_<?php echo $id; ?>">
                            <?php for($i = 1 ; $i <= $productStock ; $i++){ ?>
                                <option><?php echo $i;?> Unidad</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <?php }?>


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
                                    <button class="btn btn-outline-secondary " type="button" id="subtractQuantity">-</button>
                                </div>
                                <input type="text" class="form-control text-center" value=" ';echo $productAmount;echo '  " id="quantity" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary " type="button" id="addQuantity">+</button>
                                </div>
                            </div>
                             <label for="botoneliminar" style="cursor: grab">Eliminar producto</label>
                                <button id="botoneliminar" class="botoneliminar"  style="display: none ; " onclick="deleteThisproduct(';echo $userOrderId;echo '   )"></button>
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
                                    <button class="btn btn-outline-secondary subtractQuantity"  type="button" id="subtractQuantity">-</button>
                                </div>
                                <input type="text" class="form-control text-center" value="0" id="quantity" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary addQuantity" type="button" id="addQuantity">+</button>
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
        $(document).ready(function() {

            $("#addQuantity").click(function() {
                var currentQuantity = parseInt($("#quantity").val());
                $("#quantity").val(currentQuantity + 1);
            });


            $("#subtractQuantity").click(function() {
                var currentQuantity = parseInt($("#quantity").val());
                if (currentQuantity > 1) {
                    $("#quantity").val(currentQuantity - 1);
                }
            });
        });
        function changeInfo() {
            var name = $('#fullname').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            if (name == '' || email == "" || phone == "") {
                $('#emailWrong').show();
                $('#emailWrong').html('Faltan Campos por llenar.');
                setTimeout("$('#emailWrong').hide(); $('#emailWrong').html('')", 5000);
            } else {
                $.ajax({
                    url: "UpdateInfo.php",
                    type: "POST",
                    data: 'name=' + name + '&email=' + email + "&phone=" + phone,
                    success: function (res) {
                        location.reload();
                    },
                    error: function () {
                        alert('Archivo no encontrado.');
                    }
                });

            }
        }
        function deleteThisproduct(order){
            $.ajax({
                url:"deleteProductCar.php",
                type:"POST",
                data:'order='+order,
                success:function (res){
                    console.log(res);
                    location.reload();
                },
                error:function (){
                    alert('Archivo no encontrado.');
                }
            });
        }
    </script>
    </body>


</html>