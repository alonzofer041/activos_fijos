<?php

namespace App\Http\Controllers\Auth;

use App\Model\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use PHPMailer;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function register(Request $r){
        $this->validator($r->all())->validate();

        event(new Registered($user = $this->create($r->all())));

        //$this->guard()->login($user);

        return $this->registered($r, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        do{
            $codigoConfirmacion = str_random(100);
        }while($this->existeCodigo($codigoConfirmacion));

        $to       = $data['correo'];
        $subject  = 'Verifica tu correo electrónico';
        $message  = '<!DOCTYPE html>
                    <html lang="es-Es">
                        <head>
                            <meta charset="utf-8">
                        </head>
                        <body>
                            <h2>Por favor, verifica tu correo electrónico</h2>

                            <div>
                                Gracias por registrarte.
                                Por favor da clic en el siguiente link o copialo y pégalo en un navegador para confirmar tu correo electrónico.<br/>
                                <a href="'.url('registro/verificar/' . $codigoConfirmacion).'">'.url('registro/verificar/' . $codigoConfirmacion).'</a><br/>

                            </div>

                        </body>
                    </html>';
        $headers  = 'From: jesus.ross@outlook.com' . "\r\n" .
                    'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=utf-8';

        $usuarioCreado = Usuario::create([
                'nombre' => $data['nombre'],
                'correo' => $data['correo'],
                'password' => bcrypt($data['password']),
                'idrol' => 1,
                'codigo' => $codigoConfirmacion
            ]);
        if(!$usuarioCreado){
            Session::flash('message','<div class="alert alert-danger">No se pudo enviar el correo de verificación.</div>');
            return false;
        }
        if($this->enviarCorreo(config('global.correoSistema'), $to, $usuarioCreado->nombre, $subject, $message)){
            Session::flash('message','<div class="alert alert-info">¡Gracias por registrarte! Por favor, confirma tu correo electrónico.</div>');
            return $usuarioCreado;
            
        }
        else{
            Session::flash('message','<div class="alert alert-danger">No se pudo enviar el correo de verificación.</div>');
            return false;
        }

        

    }

    public function existeCodigo($codigoNuevo){
        $usuario = Usuario::where("codigo",$codigoNuevo)->first();
        if(!$usuario){
            return false;
        }else{
            return true;
        }
    }

    public function verificarCorreo($codigo){
        $usuario = Usuario::where("codigo",$codigo)->first();
        if(!$usuario){
            Session::flash('message','<div class="alert alert-danger">El código de verificación es incorrecto.</div>');
            return redirect()->action("HomeController@index");
        }else{
            $usuario->codigo = NULL;
            $usuario->estado = 1;
            $seGuardo = $usuario->save();
            if(!$seGuardo){
                Session::flash('message','<div class="alert alert-danger">Ocurrió un error al intentar verificar el correo electrónico.</div>');
                return redirect()->action("HomeController@index");
            }
            Session::flash('message','<div class="alert alert-success">Tu correo ha sido verificado exitosamente. Ya puedes iniciar sesión.</div>');
            return redirect('login');
        }
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
