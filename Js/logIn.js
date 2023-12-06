function CreateUser(){
    var userName= $('#username').val();
    var fullName = $('#fullname').val();
    var email = $('#email').val();
    var pass = $('#pwd').val();
    var phone = $('#phone').val();
    var birthday = $('#birthdate').val();
    if(birthday == '' || userName == '' || fullName == '' || email == '' || pass == '' || phone == '' ){
        alert('Faltan campos');
    }
    else{
        var ruta = "userName="+userName + "&fullName="+fullName + "&email="+email+"&pass="+pass+"&phone="+phone+ "&birthday="+birthday;
        $.ajax({
            url:" BackEnd/CreateUser.php",
            type:"POST",
            data:ruta,
            success:function (res){
                alert(res);
            },
            error:function (){
                alert('Archivo no encontrado.');
            }
        });
    }
}
function verifyEmail(){
    var email = $('#email').val();
    $.ajax({
        url:" BackEnd/existingEmail.php",
        type:"POST",
        data:'correo='+email,
        success:function (res){
            if(res == 1){
                $('#email').val('');
                $('#emailWrong').show();
                $('#emailWrong').html('El correo '+ email  + ' ya se encuentra registrado.');
                setTimeout("$('#emailWrong').hide(); $('#email').html('')",5000);
            }
        },
        error:function (){
            alert('Archivo no encontrado.');
        }
    });
}
function signIn(){
    var user = $('#username').val();
    var pass = $('#pwd').val();

    if(user == '' || pass == ''){
        $('#emailWrong').show();
        $('#emailWrong').html('Faltan Campos por llenar.');
        setTimeout("$('#emailWrong').hide(); $('#emailWrong').html('')",5000);
    }
    else{
        $.ajax({
            url:"BackEnd/signIn.php",
            type:"POST",
            data:'user='+user+'&pass='+pass,
            success:function (res){
                if(res == 1){
                    window.location.href='index.php';
                }
                else{
                    $('#emailWrong').show();
                    $('#emailWrong').html('Datos Incorrectos.');
                    setTimeout("$('#emailWrong').hide(); $('#emailWrong').html('')",5000);
                }
            },
            error:function (){
                alert('Archivo no encontrado.');
            }
        });
    }
}
function addToCard(id){

    var selectedQuantity = $('#cantidad_' + id).val();
    var numericValue = parseInt(selectedQuantity.replace(/\D/g, ''), 10);

   $.ajax({
        url:"newOrder.php",
        type:"POST",
        data:'id='+id+'&cantidad='+numericValue,
        success:function (res){
           console.log(res);
            location.reload();
        },
        error:function (){
            alert('Archivo no encontrado.');
        }
    });
}
