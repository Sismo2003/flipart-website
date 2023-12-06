<?php
$name = $_REQUEST['userNameContact'];
$email = $_REQUEST['userEmailContact'];
$msj = $_REQUEST['userMsgContact'];
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'alexis.ortiz6646@alumnos.udg.mx';                     //SMTP username
        $mail->Password = 'loihtyvsfqebvlve';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->setFrom('alexis.ortiz6646@alumnos.udg.mx', 'FlipART');
        $mail->addAddress($email, $name);     //Add a recipient

        //Attachments

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Gracias por tus comentarios ';
        $mail->Body = '<h1>Tus comentarios nos ayudan a mejorar muchas gracias</h1><h4>Estaremos en contacto contigo por esta via (email), asi podremos solucionar tus problemas, esprobable que te llegue un email en 24hrs<br></h4><p>'.$msj.'</p>';
        $mail->AltBody = 'Tus comentarios nos ayudan a mejorar muchas gracias, Estaremos en contacto contigo por esta via (email), asi podremos solucionar tus problemas, esprobable que te llegue un email en 24hrs     ' . $msj;

        $mail->send();
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        sleep(15);
        header('Location: index.php');
        exit();
    }

?>