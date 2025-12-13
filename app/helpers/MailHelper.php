<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
    public static function sendMail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = env('MAILER_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAILER_USERNAME');
            $mail->Password   = env('MAILER_PASSWORD');
            $mail->SMTPSecure = 'tls';
            $mail->Port       = env('MAILER_PORT', 587);

            $mail->setFrom(env('MAILER_FROM'), env('MAILER_FROM_NAME'));
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            \Log::error('Mailer Error: ' . $e->getMessage());
            return false;
        }
    }
}
