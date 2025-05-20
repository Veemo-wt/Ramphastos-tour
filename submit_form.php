<?php

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = sanitize_input($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = sanitize_input($_POST['message'] ?? '');
    
    //tu byloby gdzies sprawdzenie czy jakie pole nie jest puste ale to jest tu niepotrzebne

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
