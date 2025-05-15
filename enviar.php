<?php

require("class.PHPMailer.php");
require("class.SMTP.php");
require("class.Exception.php");

header('Content-Type: application/json');

$env = parse_ini_file('.env');
$SMTP_HOST = $env['SMTP_HOST'] ?? '';
$SMTP_PORT = $env['SMTP_PORT'] ?? 465;
$SMTP_USERNAME = $env['SMTP_USERNAME'] ?? '';
$SMTP_PASSWORD = $env['SMTP_PASSWORD'] ?? '';
$SMTP_SECURE = $env['SMTP_SECURE'] ?? 'ssl';
$FROM_EMAIL = $env['FROM_EMAIL'] ?? '';
$TO_EMAIL = $env['TO_EMAIL'] ?? '';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['answers'], $data['grupo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos']);
    exit;
}

$grupo = $data['grupo'];
$preguntas = $grupo === 'manual' ? [
    "Diámetro", "Pasaje", "Presión / Serie", "Material del Cuerpo",
    "Extremos de Conexión", "Fluido", "Temperatura", "Aclaración de funcionamiento"
] : [
    "Diámetro", "Pasaje", "Presión / Serie del Fluido", "Material del Cuerpo",
    "Extremos de Conexión", "Fluido", "Tipo de actuador",
    "Presión de suministro de actuador", "Tensión de red del actuador",
    "ΔP", "Temperatura"
];

try {
    $mail = new PHPMailer(true);

    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';

    $mail->isSMTP();
    $mail->Host = $SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = $SMTP_USERNAME;
    $mail->Password = $SMTP_PASSWORD;
    $mail->SMTPSecure = $SMTP_SECURE;
    $mail->Port = $SMTP_PORT;

    $mail->setFrom($FROM_EMAIL, 'Consulta Web');
    $mail->addAddress($TO_EMAIL);
    $mail->isHTML(true);
    $mail->Subject = 'Nueva Consulta Técnica de Válvulas';

    $body = "<strong>Tipo de válvula:</strong> " . ucfirst($grupo) . "<br><br>";
    foreach ($data['answers'] as $i => $resp) {
        $preg = $preguntas[$i] ?? "Pregunta $i";
        $body .= "<strong>$preg:</strong> " . htmlspecialchars($resp) . "<br>";
    }
    $mail->Body = $body;
    $mail->send();

    $file = __DIR__ . '/consultas.csv';
    $isNew = !file_exists($file);
    $row = [date('Y-m-d H:i:s'), ucfirst($grupo), ...$data['answers']];
    $f = fopen($file, 'a');
    if ($isNew) {
        fputcsv($f, ['Fecha', 'Tipo', ...$preguntas]);
    }
    fputcsv($f, $row);
    fclose($f);

    echo json_encode(['status' => 'OK']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al enviar: ' . $mail->ErrorInfo]);
}
