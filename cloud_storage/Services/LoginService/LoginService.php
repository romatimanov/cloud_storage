<?php

namespace App\Services\LoginService;

use PHPMailer\PHPMailer\PHPMailer;

class LoginService
{
    public function sendMail($email)
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP(true);
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'ssl://smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->Username = 'romatimanov77@gmail.com';
        $mail->Password = 'laqu tste sssr ngex';

        $mail->setFrom('romatimanov77@gmail.com', 'name');
        $mail->addAddress($email, 'Recipient Name');

        $mail->Subject = 'Сброс пароля';
        $mail->Body = "Для сброса пароля перейдите по ссылке:";

        $mail->send();
    }
}
