<?php

namespace App\Http\Controllers\AuthCliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Model\Cliente;

class LoginControllerCliente extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/venta';
    //protected $guard = 'ventaenlinea';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'email';
    }

//     public function redirectTo() {
//     $user = Auth::user();
//     switch(true) {
//         case $user->isInstructor():
//             return '/instructor';
//             break;
//         case $user->isAdmin():
//         case $user->isSuperAdmin():
//             return '/admin';
//             break;
//         default:
//             return '/account';
//     }
// }

    public function logOut()
    {
        //Auth::logout();
        Session::flush();
        //return view('login')->with('mensaje', array("class"=>"danger","mensaje"=>'Tu sesión ha sido cerrada.'))->with('active', "login");
        Session::flash('message', '<div class="alert alert-danger">Tu sesión ha sido cerrada.</div>');
        return redirect('/login')->with('mensaje', array("class"=>"danger","mensaje"=>'Tu sesión ha sido cerrada.'))->with('active', "login");
    }

    /*public function login(Request $r)
    {
        // Guardamos en un arreglo los datos del usuario.
        $userdata = $r->all();
        $credentials = array(
            'email' => $userdata['email'], 
            'password' => $userdata['password'],
            'estado' => 1
        );

        $recordar = false;
        
        // Validamos los datos y además mandamos como un segundo parámetro la opción de recordar el usuario.
        if(Auth::attempt($credentials,$recordar))
        {

            
            return redirect()->action('HomeController@index');
        }
        // En caso de que la autenticación haya fallado manda un mensaje al formulario de login y también regresamos los valores enviados con withInput().
        Session::flash('flash_message', '<div class="alert alert-danger">Tus datos son incorrectos.</div>');
        return  redirect('login')->withInput()->with('mensaje', array("class"=>"danger", "mensaje" =>"Tus datos son incorrectos"))->with("active","login");
    }*/


    protected function attemptLogin(Request $request)
    {
        
        $context=$request->all();

        $this->redirectTo=$context['vcgfds'];
        
        $user =Cliente::where('email',$context['email'])
                  ->where('pass',md5($context['password']))
                  ->first();       
        

        if ($user) {             
            $this->guard()->login($user, $request->has('remember'));            
            return true;
        }

        return false;
    }

    protected function guard()
    {
        return Auth::guard('ventaenlinea');
    }

    protected function validateLogin(Request $request)
    {
        
        $this->validate($request, [
            $this->username() => 'required|string',
            'pass' => 'required|string',
        ]);
    }

    public function login(Request $request)
    {
        
        //$this->validateLogin($request);
        
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        //$request->request->add(['estado'=> 1]);
        //var_dump($request);


        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        Session::flash('message', '<div class="alert alert-danger">Tus datos son incorrectos.</div>');
        return $this->sendFailedLoginResponse($request);
    }
    
    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {

        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        //aqui hacemos la logica del login
        //1 creamos la variable de sesion
        session(['idusuario_eticket' => $this->guard()->user()->id_cliente]);
        //2 Verficamos si para ese usuario hay un carrito abierto existente
        //aqui creamos la logica del login

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    protected function credentials(Request $request)
    {
        //return $request->only($this->username(), 'password','estado');
        return $request->only($this->username(), 'pass');
    }
}
