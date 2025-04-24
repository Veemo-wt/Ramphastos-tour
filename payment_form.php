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

    $data = [
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    $dir = 'payments';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    $filename = $dir . '/' . date('Y-m-d_H-i-s') . '.json';
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));

} else {
    header('Location: contact.html');
    exit();
}
?>
