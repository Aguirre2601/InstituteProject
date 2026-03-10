<?php
namespace App\Services;

// Importar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    
    protected $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true); // true habilita excepciones
        
        // Configuración del Servidor (EJEMPLO CON GMAIL)
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com'; // O tu servidor SMTP
        $this->mail->SMTPAuth   = true;
        
        //  Reemplaza con tus credenciales SMTP
        $this->mail->Username   = 'TuCorre@correo.com'; 
        $this->mail->Password   = 'tucontraseña'; // Usa una contraseña de aplicación si es Gmail
        
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Usar TLS
        $this->mail->Port       = 587; // Puerto para TLS
        
        // Remitente por defecto
        $this->mail->setFrom('no-reply@instituto.com', 'ISFDyT');
    }

    /**
     * Envía las credenciales al nuevo profesor.
     */
    public function enviarCredenciales($destinatarioEmail, $nombre, $usuario, $contrasena) {
        try {
            $this->mail->addAddress($destinatarioEmail, $nombre);
            
            // Contenido
            $this->mail->isHTML(true); 
            $this->mail->Subject = '¡Bienvenido/a! Tus Credenciales de Acceso';
            
            // Cuerpo del Email (Puedes usar una plantilla HTML más elaborada)
            $cuerpo = "
                <h1>Hola {$nombre},</h1>
                <p>Has sido registrado/a como Profesor/a en nuestro sistema.</p>
                <p>Usa las siguientes credenciales para iniciar sesión:</p>
                <ul>
                    <li><strong>Usuario:</strong> {$usuario}</li>
                    <li><strong>Contraseña:</strong> {$contrasena}</li>
                </ul>
                <p>Por favor, cambia tu contraseña después del primer inicio de sesión.</p>
                <p>Saludos,<br>Instituto 93</p>
            ";
            
            $this->mail->Body = $cuerpo;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Loguea el error o muestra un mensaje
            error_log("Error al enviar el email: {$this->mail->ErrorInfo}");
            return false;
        }
    }
    /**
     * Envía las credenciales al nuevo profesor.
     */
    public function recuperarCredenciales($destinatarioEmail, $nombre, $usuario, $contrasena) {
        try {
            $this->mail->addAddress($destinatarioEmail, $nombre);
            
            // Contenido
            $this->mail->isHTML(true); 
            $this->mail->Subject = '¡Bienvenido/a! Tus Credenciales de Acceso';
            
            // Cuerpo del Email (Puedes usar una plantilla HTML más elaborada)
            $cuerpo = "
                <h1>Hola {$nombre},</h1>
                <p>Has solicitado recuperar tus credenciales de acceso en nuestro sistema.</p>
                <p>Usa las siguientes credenciales para iniciar sesión:</p>
                <ul>
                    <li><strong>Usuario:</strong> {$usuario}</li>
                    <li><strong>Contraseña:</strong> {$contrasena}</li>
                </ul>
                <p>Por favor, cambia tu contraseña después del primer inicio de sesión.</p>
                <p>Saludos,<br>Instituto 93</p>
            ";
            
            $this->mail->Body = $cuerpo;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Loguea el error o muestra un mensaje
            error_log("Error al enviar el email: {$this->mail->ErrorInfo}");
            return false;
        }
    }
}
