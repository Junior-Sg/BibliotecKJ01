<?php

class CorreoController
{
    public function prueba()
    {
        require_once __DIR__ . "/../Librerias/PHPMailer/PHPMailer.php";
        require_once __DIR__ . "/../Librerias/PHPMailer/SMTP.php";
        require_once __DIR__ . "/../Librerias/PHPMailer/Exception.php";

        $mail = new PHPMailer\PHPMailer\PHPMailer();

        try {
            // Configuración SMTP de Mailtrap
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'TU_USERNAME';
            $mail->Password = 'TU_PASSWORD';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            // Remitente y destinatario
            $mail->setFrom('biblioteca@test.com', 'BibliotecKJ');
            $mail->addAddress('tu_correo_de_prueba@gmail.com');

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Prueba de correo desde BibliotecKJ';
            $mail->Body = '<h1>Funciona correctamente</h1><p>Correo de prueba con PHPMailer.</p>';

            if ($mail->send()) {
                echo "CORREO ENVIADO EXITOSAMENTE.";
            } else {
                echo "ERROR AL ENVIAR CORREO: " . $mail->ErrorInfo;
            }

        } catch (Exception $e) {
            echo "EXCEPCIÓN: " . $e->getMessage();
        }
    }
}
