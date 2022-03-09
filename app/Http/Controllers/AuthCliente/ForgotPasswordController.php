<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use PHPMailer;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $datos = $request->all();
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        /*$response = $this->broker()->sendResetLink(
            $request->only('email')
        );*/

        $link = "passwords.sent";

        $user = $this->broker()->getUser($request->only('correo'));
        if (is_null($user)) {
           $link = 'passwords.user';
        }
        //var_dump($this->broker());
        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $token = $this->broker()->getRepository()->create($user);
        $mensaje = 'Estás recibiendo este mensaje porque olvidaste tu contraseña y la quieres recuperar <br/><a href="'.action('\App\Http\Controllers\Auth\ResetPasswordController@showResetForm', ["token"=>$token])
            .'">'.action('\App\Http\Controllers\Auth\ResetPasswordController@showResetForm', ["token"=>$token])
            .'</a><br/>Si tu no has realizado esta solicitud, ignora este correo y te sugerimos que cambies tu contraseña';
        $this->enviarCorreo(config('global.correoSistema'),$datos['correo'],"","Reiniciar contraseña",$mensaje);
        

        

        return $link == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($link)
                    : $this->sendResetLinkFailedResponse($request, $link);
    }

    public function enviarCorreo($from,$to,$nombreTo,$subject,$mensaje){
        $mail = new PHPMailer;
        
        $from = config('global.correoSistema'); // sender

        $archivo = "";//se crea archivo pdf


        $mail->setFrom($from, 'Orquesta Sinfónica');
        $mail->addAddress($to, $nombreTo);     // Add a recipient

        //$mail->addAttachment("public/archivos/".$archivo,$archivo);// Add attachments
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $mensaje;
        $mail->CharSet = 'UTF-8';

        if(!$mail->send()){

            return false;

            
        }else{
            return true;
            
        }
    }
}
