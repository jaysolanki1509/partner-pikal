<?php namespace App\Http\Middleware;

use App\Owner;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Response;

class ApiAuth {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
        $token = $_SERVER["HTTP_AUTHORIZATION"];

        if ( isset($token) && $token != '' ) {

            $check_token = Owner::where('api_token',$token)->first();

            if ( isset($check_token) && sizeof($check_token) > 0 ) {
                return $next($request);
            }
        }

        return Response::json(array(
            'message' => 'Unauthorized',
            'statuscode' => 401,
            'status'=>'Error'
        ),401);

    }

}
