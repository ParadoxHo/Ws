<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';
    $attachment = isset($_FILES['attachment']) ? $_FILES['attachment'] : null;

    $to = "alarmonix@gmail.com"; // Ваш email
    $subject = "Nowa wiadomość z Alarmonix";
    
    $boundary = md5(time());
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= "Imię: $name\nTelefon: $phone\nEmail: $email\n\nWiadomość:\n$message\n";
    $body .= "--$boundary\r\n";
    
    if ($attachment && $attachment['error'] == 0 && $attachment['size'] > 0) {
        $fileContent = chunk_split(base64_encode(file_get_contents($attachment['tmp_name'])));
        $body .= "Content-Type: " . $attachment['type'] . "; name=\"" . $attachment['name'] . "\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"" . $attachment['name'] . "\"\r\n\r\n";
        $body .= $fileContent . "\r\n";
        $body .= "--$boundary\r\n";
    }
    
    $body .= "--$boundary--";
    
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["status" => "ok", "message" => "Wiadomość wysłana"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Błąd wysyłki"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Nieprawidłowe żądanie"]);
}
?>
