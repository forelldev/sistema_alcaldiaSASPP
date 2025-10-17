<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require_once 'envLoader.php';
loadEnv(__DIR__ . '/.env');
class Correo {
    private static function configurarMailer($correo, $nombre, $asunto, $mensajeHTML) {
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username = getenv('GMAIL_USER');
            $mail->Password = getenv('GMAIL_PASS');
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom(getenv('GMAIL_USER'), 'Correos Notificaciones');
            $mail->addAddress($correo, $nombre);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $mensajeHTML;

            $mail->send();
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    }

    public static function correoVencido($correo, $nombre) {
        $asunto = 'Ya han pasado 5 días y su solicitud aún no ha sido aprobada!';
        $mensaje = 'Estamos haciendo lo posible, pronto será renovada su solicitud. Recibirá un correo de confirmación.';
        self::configurarMailer($correo, $nombre, $asunto, $mensaje);
        return true;
    }

    public static function correoRenovado($correo, $nombre) {
        $asunto = 'Su solicitud ha sido renovada. Se darán 5 días más para su solicitud.';
        $mensaje = 'Estamos haciendo lo posible por su solicitud. Ha sido renovada y se le avisará en los próximos 5 días.';
        self::configurarMailer($correo, $nombre, $asunto, $mensaje);
        return true;
    }

    public static function correoClave($correo,$nombre,$codigo){
        $asunto = 'Su código para el cambio de contraseña es...';
        $mensaje = 'Su código para su cambio de contraseña es: '.$codigo;
        self::configurarMailer($correo, $nombre, $asunto, $mensaje);
        return true;
    }
}

?>