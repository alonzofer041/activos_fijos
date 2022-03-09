<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer;


class testPHPMailer extends Controller
{
    public function index()
    {
        $text             = 'PRUEBA DE MeNSAJE';
        $mail             = new PHPMailer\PHPMailer(); // create a n
        $mail->SMTPDebug  = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth   = false; // authentication enabled
        //$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host       = "localhost";
        $mail->Port       = 1025; // or 587
        $mail->Issmtp(true);
        //$mail->Username = "solachegames666@gmail.com";
        //$mail->Password = "";
        $mail->SetFrom("testmail@gmail.com", 'CARLOS PUC');
        $mail->Subject = "PRUEBA";
       $mail->Body    = $text;
        $mail->AddAddress("testreciver@gmail.com", "Receiver Name");
        if ($mail->Send()) {
            return 'Email Sended Successfully';
        } else {
            return 'Failed to Send Email';
        }
    }
}