<?php
// Configuración de destino
$email_destino = "info@laboratoriotesting.com";
$asunto_correo = "Nuevo mensaje desde el sitio web Laboratorio Testing";

// Verificar que la petición sea vía POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitización y limpieza de campos
    $nombre   = isset($_POST['nombre']) ? filter_var(trim($_POST['nombre']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $email    = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $telefono = isset($_POST['telefono']) ? filter_var(trim($_POST['telefono']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'No especificado';
    $mensaje  = isset($_POST['mensaje']) ? filter_var(trim($_POST['mensaje']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    // Validar campos obligatorios
    if (empty($nombre) || empty($email) || empty($mensaje) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html?status=error_datos#contacto");
        exit;
    }

    // Construcción del cuerpo del correo en HTML
    $cuerpo = "
    <html>
    <head>
        <title>$asunto_correo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; max-width: 600px; }
            .header { background-color: #f4f4f4; padding: 10px 15px; border-radius: 5px; font-weight: bold; }
            .field { margin-bottom: 10px; }
            .label { font-weight: bold; color: #555; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>Detalles de la consulta recibida</div>
            <br>
            <div class='field'><span class='label'>Nombre:</span> $nombre</div>
            <div class='field'><span class='label'>Correo electrónico:</span> $email</div>
            <div class='field'><span class='label'>Teléfono:</span> $telefono</div>
            <div class='field'><span class='label'>Mensaje:</span><br>" . nl2br($mensaje) . "</div>
        </div>
    </body>
    </html>
    ";

    // Encabezados obligatorios para Hostinger (evita la carpeta de Spam)
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Formulario Web <info@laboratoriotesting.com>" . "\r\n";
    $headers .= "Reply-To: $nombre <$email>" . "\r\n";

    // Envío del correo
    if (mail($email_destino, $asunto_correo, $cuerpo, $headers)) {
        // Redirección con parámetro de éxito
        header("Location: index.html?status=success#contacto");
        exit;
    } else {
        // Redirección si falla el servidor de correo
        header("Location: index.html?status=error_envio#contacto");
        exit;
    }

} else {
    // Si intenta acceder directamente al script sin enviar formulario
    header("Location: index.html");
    exit;
}
?>
