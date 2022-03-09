<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
use App\Model\Rol;

class LoginController extends Controller
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

    protected $redirectTo = '/home';

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/agente';

     public function home(){        
        return view("auth.login");
    }

    public function redirectTo(){
        
        // User role
        $role = Auth::user()->rol->url; 
        
        return $role;
    }

    

     public function logOut()
    {
        
        //Auth::logout();
        Session::flush();
        //return view('login')->with('mensaje', array("class"=>"danger","mensaje"=>'Tu sesión ha sido cerrada.'))->with('active', "login");
        Session::flash('message', '<div class="alert alert-danger">Tu sesión ha sido cerrada.</div>');
        return redirect('/login')->with('mensaje', array("class"=>"danger","mensaje"=>'Tu sesión ha sido cerrada.'))->with('active', "login");
    }

    public function redirectPath(){    
        /*
        Aqui se hace la logica para que dependiendo del rol del usuario se redirija a una pagina
        */
        $user = Auth::user();
        $rol=Rol::find($user->idrol);
        if($rol){
          if($rol->url!=''){            
            return action($rol->url);
          }
          else{
            return "/home";
          }

        }
        else
        return "/home";        
    }

    public function login(Request $r)
    {
        // Guardamos en un arreglo los datos del usuario.
        $userdata = $r->all();
        $credentials = array(
            'email' => $userdata['email'], 
            'password' => $userdata['password']
        );



        
        
        // Validamos los datos y además mandamos como un segundo parámetro la opción de recordar el usuario.
        if(Auth::attempt($credentials))
        {  
          return $this->authenticated($r, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
        }
        else{
        // En caso de que la autenticación haya fallado manda un mensaje al formulario de login y también regresamos los valores enviados con withInput().
        Session::flash('flash_message', '<div class="alert alert-danger">Tus datos son incorrectos.</div>');
        return  redirect('/')->withInput()->with('mensaje', array("class"=>"danger", "mensaje" =>"Tus datos son incorrectos"))->with("active","login");    
        }
        
    }

    public function username()
    {
        return 'email';
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request); 

        
        // dd($this->authenticated($request, $this->guard()->user()));

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    protected function credentials(Request $request)
    {
        //return $request->only($this->username(), 'password','estado');
        return $request->only($this->username(), 'password');
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
