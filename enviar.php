<?php
// Configuración de destino
$email_destino = "info@laboratoriotesting.com";
$asunto_correo = "Solicitud de Cotización desde el sitio web - Testing";

// Verificar recepción vía POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitización de los datos recibidos
    $nombre      = isset($_POST['nombre']) ? filter_var(trim($_POST['nombre']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $telefono    = isset($_POST['telefono']) ? filter_var(trim($_POST['telefono']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $email       = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $ubicacion   = isset($_POST['ubicacion']) ? filter_var(trim($_POST['ubicacion']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'No especificada';
    $tipo_ensayo = isset($_POST['tipo_ensayo']) ? filter_var(trim($_POST['tipo_ensayo']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'Sin seleccionar';
    $mensaje     = isset($_POST['mensaje']) ? filter_var(trim($_POST['mensaje']), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'Sin detalles adicionales';

    // Validar campos obligatorios
    if (empty($nombre) || empty($email) || empty($telefono) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contacto.html?status=error_datos#contacto");
        exit;
    }

    // Construcción del diseño del correo en HTML
    $cuerpo = "
    <html>
    <head>
        <title>$asunto_correo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { padding: 20px; border: 1px solid #e0e0e0; border-radius: 12px; max-width: 600px; background-color: #ffffff; }
            .header { background-color: #e05d06; color: #ffffff; padding: 15px; border-radius: 8px 8px 0 0; font-weight: bold; font-size: 18px; }
            .content { padding: 15px; }
            .field { margin-bottom: 12px; }
            .label { font-weight: bold; color: #555; display: inline-block; width: 160px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>Nueva Solicitud de Cotización</div>
            <div class='content'>
                <div class='field'><span class='label'>Cliente / Empresa:</span> $nombre</div>
                <div class='field'><span class='label'>Correo Electrónico:</span> $email</div>
                <div class='field'><span class='label'>Teléfono:</span> $telefono</div>
                <div class='field'><span class='label'>Ubicación Obra:</span> $ubicacion</div>
                <div class='field'><span class='label'>Tipo de Ensayo:</span> $tipo_ensayo</div>
                <hr style='border: 0; border-top: 1px solid #eee; margin: 15px 0;'>
                <div class='field'><span class='label'>Detalles:</span><br>" . nl2br($mensaje) . "</div>
            </div>
        </div>
    </body>
    </html>
    ";

    // Encabezados
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Web Testing <info@laboratoriotesting.com>" . "\r\n";
    $headers .= "Reply-To: $nombre <$email>" . "\r\n";

    // Envío del correo
    if (mail($email_destino, $asunto_correo, $cuerpo, $headers)) {
        header("Location: contacto.html?status=success#contacto");
        exit;
    } else {
        header("Location: contacto.html?status=error_envio#contacto");
        exit;
    }

} else {
    header("Location: contacto.html");
    exit;
}
?>
