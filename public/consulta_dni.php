<?php
// Endpoint: public/consulta_dni.php
// Recibe POST { dni } y consulta la API pública de RENIEC (apis.net.pe) con token proporcionado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$token = 'apis-token-11088.4YrFYiVdQdkaLfvTgZpsp9DMEE4CNrIo'; // Token de ejemplo (proveído)
$dni = $_POST['dni'] ?? '';
$dni = preg_replace('/[^0-9]/', '', $dni);

if (strlen($dni) !== 8) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'DNI inválido']);
    exit;
}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 2,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Referer: https://apis.net.pe/consulta-dni-api',
        'Authorization: Bearer ' . $token
    ),
));

$response = curl_exec($curl);

if (curl_errno($curl)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error en cURL: ' . curl_error($curl)]);
    curl_close($curl);
    exit;
}

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

header('Content-Type: application/json');
http_response_code($httpCode ?: 200);

// Reenviar la respuesta tal cual (la API ya devuelve JSON con campos como 'nombres', 'apellidoPaterno', 'apellidoMaterno')
echo $response;
