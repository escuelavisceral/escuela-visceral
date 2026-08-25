<?php
/**
 * Snippet: Envío del formulario de inscripción — Escuela Visceral
 * Pega ESTE archivo completo (incluyendo el "<?php" de la primera línea)
 * dentro del plugin "Code Snippets", como un snippet nuevo activado
 * para "Ejecutar en todo el sitio (frontend y backend)".
 */

add_action('wp_ajax_visceral_inscripcion', 'visceral_inscripcion_handler');
add_action('wp_ajax_nopriv_visceral_inscripcion', 'visceral_inscripcion_handler');

function visceral_inscripcion_handler(){
    $nombre      = sanitize_text_field($_POST['nombre'] ?? '');
    $celular     = sanitize_text_field($_POST['celular'] ?? '');
    $email       = sanitize_email($_POST['email'] ?? '');
    $programa    = sanitize_text_field($_POST['programa'] ?? '');
    $modalidad   = sanitize_text_field($_POST['modalidad'] ?? '');
    $ciudad      = sanitize_text_field($_POST['ciudad'] ?? '');
    $barrio      = sanitize_text_field($_POST['barrio'] ?? '');
    $comentarios = sanitize_textarea_field($_POST['comentarios'] ?? '');

    if (empty($nombre) || empty($celular) || !is_email($email)) {
        wp_send_json_error(['msg' => 'Datos incompletos']);
    }

    $to      = ['admisiones@elvisceral.org', 'escuelavisceral@gmail.com'];
    $subject = 'Nueva solicitud de inscripción — Escuela Visceral';
    $body    = "Nombre: $nombre\nCelular/WhatsApp: $celular\nCorreo: $email\nPrograma de interés: $programa\nModalidad: $modalidad\nCiudad: $ciudad\nBarrio: $barrio\n\nComentarios adicionales:\n$comentarios";
    $headers = ['Content-Type: text/plain; charset=UTF-8', "Reply-To: $email"];

    $sent = wp_mail($to, $subject, $body, $headers);

    if ($sent) {
        wp_send_json_success();
    } else {
        wp_send_json_error(['msg' => 'wp_mail fallo']);
    }
}
