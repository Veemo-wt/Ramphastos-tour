<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $data = [
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    $dir = 'messages';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    $filename = $dir . '/' . date('Y-m-d_H-i-s') . '.json';
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));

    echo "<script>
      alert('Oczekujemy 6 z niecierpliwością.\\n\\nPS. Ten formularz tak naprawdę nie działa.');
      window.location.href = 'contact.html';
    </script>";
} else {
    header('Location: contact.html');
    exit();
}
?>
