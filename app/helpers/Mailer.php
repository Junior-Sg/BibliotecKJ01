<!-- <!-- <?php -->
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require_once __DIR__ . '/../Librerias/PHPMailer/Exception.php';
// require_once __DIR__ . '/../Librerias/PHPMailer/PHPMailer.php';
// require_once __DIR__ . '/../Librerias/PHPMailer/SMTP.php';

// class Mailer
// {
//     private $host = 'smtp-relay.brevo.com'; // Nuevo HOST
//     private $username = '9ddf02001@smtp-brevo.com'; // Usuario SMTP Brevo
//    $smtpKey = getenv('SENDINBLUE_SMTP_KEY'); // Cambia por tu clave SMTP de Brevo
//     private $port = 587; // Puerto recomendado
//     private $fromEmail = 'bibli0teckj01@gmail.com'; // DEBE estar verificado en Brevo
//     private $fromName = 'Sistema BibliotecKJ';
//     private $projectName = 'BibliotecKJ';
//     private $logoUrl = '/BibliotecKJ01/public/img/Logos/L1.jpg';

//     public function send($to, $subject, $bodyContent)
    // {
        // $mail = new PHPMailer(true);

        // try {
        //     // Configuración SMTP Brevo
        //     $mail->isSMTP();
        //     $mail->Host = $this->host;
        //     $mail->SMTPAuth = true;
        //     $mail->Username = $this->username;
        //     $mail->Password = $this->password;
        //     $mail->Port = $this->port;
        //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        //     $mail->SMTPDebug = 0;
        //     $mail->CharSet = 'UTF-8';
        //     // Desactivar verificación SSL para evitar problemas con certificados de Brevo
        //     $mail->SMTPOptions = array(
        //         'ssl' => array(
        //             'verify_peer' => false,
        //             'verify_peer_name' => false,
        //             'allow_self_signed' => true
        //         )
        //     );

    //         // Remitente y receptor
    //         $mail->setFrom($this->fromEmail, $this->fromName);
    //         $mail->addReplyTo($this->fromEmail, $this->fromName);
    //         $mail->addAddress($to);

    //         // Intentar incrustar logo localmente para evitar problemas de carga remota
    //         $logoLocal = realpath(__DIR__ . '/../../public/img/Logos/L1.jpg');
    //         $logoCid = null;
    //         if ($logoLocal && file_exists($logoLocal)) {
    //             try {
    //                 $logoCid = 'logo_cid';
    //                 $mail->addEmbeddedImage($logoLocal, $logoCid, basename($logoLocal));
    //             } catch (Exception $e) {
    //                 error_log("No se pudo incrustar el logo: " . $e->getMessage());
    //                 $logoCid = null;
    //             }
    //         }

    //         // Contenido
    //         $mail->isHTML(true);
    //         $mail->Subject = $subject;
    //         $logoSrc = $logoCid ? ('cid:' . $logoCid) : $this->logoUrl;
    //         $mail->Body = $this->generateTemplate($subject, $bodyContent, $logoSrc);
    //         $mail->AltBody = strip_tags(html_entity_decode($bodyContent));

    //         $resultado = $mail->send();

    //         if ($resultado) {
    //             error_log("✓ Email enviado exitosamente a: $to | Asunto: $subject");
    //         } else {
    //             error_log("✗ Error en send() para: $to | Error: " . $mail->ErrorInfo);
    //         }

    //         return $resultado;

    //     } catch (Exception $e) {
    //         error_log("✗ EXCEPCIÓN al enviar correo a $to: " . $e->getMessage());
    //         return false;
    //     }
    // }

    // private function generateTemplate($title, $content, $logoSrc = null)
    // {
//         $year = date('Y');
//         $logo = htmlspecialchars($logoSrc ?? $this->logoUrl);
//         $proj = htmlspecialchars($this->projectName);
//         $titleEsc = htmlspecialchars($title);

//         $html = <<<HTML
//         <div style="width:100%;padding:20px;background:#f4f4f4;font-family:Arial, Helvetica, sans-serif;">
//             <div style="max-width:600px;margin:0 auto;background:white;border-radius:10px;overflow:hidden;
//                 border:1px solid #d6d6d6;box-shadow:0 2px 8px rgba(0,0,0,0.1);">

//                 <div style="background:#ffffff;padding:16px 20px;border-bottom:1px solid #ececec;display:flex;align-items:center;gap:12px;">
//                     <img src="{$logo}" alt="{$proj} logo" style="width:56px;height:56px;object-fit:cover;border-radius:6px;">
//                     <div style="font-size:18px;color:#1F4E79;font-weight:700;">{$proj}</div>
//                 </div>

//                 <div style="padding:25px;color:#333;font-size:15px;line-height:1.5;">
//                     <h2 style="color:#4E342E;font-weight:600;margin-top:0;">{$titleEsc}</h2>

//                     <div style="padding:15px;background:#f8f3f0;border-left:4px solid #795548;
//                         font-size:14px;color:#4E342E;border-radius:5px;">
//                         {$content}
//                     </div>

//                     <p style="margin-top:25px;color:#6d6d6d;font-size:13px;">
//                         Si tienes preguntas, comunícate con la administración de la biblioteca.
//                     </p>
//                 </div>

//                 <div style="background:#f7f7f7;padding:12px;text-align:center;color:#5d4037;
//                     font-size:12px;border-top:1px solid #d6d6d6;display:flex;align-items:center;justify-content:center;gap:8px;">
//                     <img src="{$logo}" alt="{$proj} logo" style="width:24px;height:24px;object-fit:cover;border-radius:3px;">
//                     <div>© {$year} {$proj} — Todos los derechos reservados.</div>
//                 </div>
//             </div>
//         </div>
// HTML;

//         return $html;
//     }
// } -->
