<?php namespace App\Http\Controllers\Auth;

use App\Account;
use App\City;
use App\Http\Controllers\Controller;
use App\Language;
use App\OutletMapper;
use App\State;
use Illuminate\Http\Request;

use Illuminate\Contracts\Auth\Guard;
use App\Services\Registrar;

use Illuminate\Support\Facades\Session;
use View;

use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Owner;
use App\Country;

use Illuminate\Support\Facades\Mail;
class AuthController extends Controller  {
    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * The registrar implementation.
     *
     * @var \Illuminate\Contracts\Auth\Registrar
     */
    protected $registrar;

    /*
     *
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    //use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
     * @return void
     */

    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
    }



    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        $language=Language::all();
        $states = State::all();
        $cities = City::all();
        return view('auth.register',array('language'=>$language,'states'=>$states,'cities'=>$cities));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->registrar->validator($request->all());

        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $ownerid=$this->registrar->create($request->all());
        //$pass=str_random(6);
        $email_req=$request->email;
        $user_name = $request->user_name;
        $email = isset($email_req)?$request->email:'null';
        $contact_no = $request->contact_no;
        $receive_mail = 'dev@savitriya.com';

        Mail::send('emails.newpassword', ['username'=>$user_name, 'email'=>$email, 'contact_no'=>$contact_no], function($message) use ($receive_mail)
        {
            $message->from('we@pikal.io', 'Pikal');
            $message->to($receive_mail, 'Pikal');
            $message->subject('Pikal : New Registeration');
        });

        return redirect($this->redirectPath());
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('auth.login');
    }



    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {

        $usernameinput = Input::get('email');
        $password = Input::get('password');
        $remember = Input::get('remember');

        $field = filter_var($usernameinput, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        //check useris active? account table
        $owner = Owner::where($field, $usernameinput)->select('account_id','web_login')->first();
        // if (isset($owner) && sizeof($owner) > 0){
        if (!empty($owner) && (is_array($owner) || $owner instanceof Countable)) {

            $account_id = $owner->account_id;
            $account_status = Account::find($account_id);
            if (isset($account_status) && sizeof($account_status) > 0) {
               $is_active = $account_status->active;
                if ($is_active == 0) {
                    return redirect($this->loginPath())
                        ->withInput($request->only('email', 'remember'))
                        ->withErrors([
                            'email' => 'Account is not Active, please contact Pikal.',
                        ]);
                }
            }
        }

        if($this->auth->attempt(array($field => $usernameinput, 'password' => $password,'deleted_at'=>NULL),$remember))
        {
            if ( $owner->web_login == 0 ) {

                $this->auth->logout();

                return redirect($this->loginPath())
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'you are not entitled for "Web Login"'
                    ]);

            }
            Session::set('outlet_session','');

            //set outlet session
            $ot_obj = OutletMapper::getOutletsByOwnerId();

            unset($ot_obj[""]);
            $count = 0;
            foreach( $ot_obj as $o_key=>$o_val ){

                if( $count == 0 ){
                    Session::set('outlet_session',$o_key);
                }
                $count++;
            }

            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath())
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return 'These credentials do not match our records.';
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }



    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */


    public function redirectPath()
    {
        if (property_exists($this, 'redirectPath'))
        {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/owner';
    }


}