<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if vendor/autoload.php exists
if (!file_exists('vendor/autoload.php')) {
    echo json_encode(['status' => 'error', 'message' => 'PHPMailer is not installed. Please run composer install.']);
    exit;
}

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $message = $_POST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'mail.virtualhutch.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@virtualhutch.com';
        $mail->Password = 'TXW9krm)VNzP';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        // Additional headers for better deliverability
        $mail->XMailer = 'KitchenScape Contact Form';
        $mail->Priority = 1;
        $mail->addCustomHeader('X-MSMail-Priority', 'High');
        $mail->addCustomHeader('Importance', 'High');
        $mail->addCustomHeader('X-Bulk-Mail', 'No');

        // Recipients
        $mail->setFrom('no-reply@virtualhutch.com', 'KitchenScape Contact Form');
        $mail->addReplyTo($email);
        $mail->addAddress('hello@kitchenscape.ae', 'KitchenScape Team');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission - KitchenScape from website';
        $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #f8f9fa; padding: 20px; margin-bottom: 20px; }
                    .content { padding: 20px; }
                    .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>New Contact Form Submission</h2>
                    </div>
                    <div class='content'>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Phone:</strong> $phone</p>
                        <p><strong>Address:</strong> $address</p>
                        <p><strong>Message:</strong><br>$message</p>
                    </div>
                    <div class='footer'>
                        <p>This email was sent from the KitchenScape contact form.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Message sent successfully! Please check your email.']);
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo json_encode(['status' => 'error', 'message' => "Message could not be sent. Please try again later."]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
