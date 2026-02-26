<?php namespace App\Exceptions;

use App\Outlet;
//use Illuminate\Support\Facades\Exception;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Swift_Mailer;
use Swift_SmtpTransport;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
        if ($e instanceof Exception) {

            if (env("APP_ENV") == "jlkfdf") {

                $outlet = Outlet::find(Session::get('outlet_session'));

                $content = "There was an error occured on production.following are details of Error:";
                $content .= "<br><br>Message : ".$e->getMessage();
                $content .= "<br>Line : ".$e->getLine();
                $content .= "<br>File : ".$e->getFile();
                $content .= "<br>User : ".Auth::user()->user_name;
                $content .= "<br>User : ".$outlet->name;
                $content .= "<br>Environment : ".env("APP_ENV");

                // Backup your default mailer
                $backup = Mail::getSwiftMailer();

                // Setup your gmail mailer
                $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587, 'tls');
                $transport->setUsername('np@savitriya.com');
                $transport->setPassword('Nitin@123');
                // Any other mailer configuration stuff needed...

                $gmail = new Swift_Mailer($transport);

                // Set the mailer as gmail
                Mail::setSwiftMailer($gmail);


                // emails.exception is the template of your email
                // it will have access to the $error that we are passing below
                Mail::raw($content, function ($m) {
                    $m->to('np@savitriya.com', 'Nitin')->subject('Exception occurred on production');
                });
                // Restore your original mailer
                Mail::setSwiftMailer($backup);

            }

        }
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
        if($this->isHttpException($e))
        {
            switch ($e->getStatusCode())
            {
                // not found
                case 404:
                    return \Response::view('errors.404',array(),404);
                    break;

                // internal error

                default:
                    return $this->renderHttpException($e);
                    break;
            }
        }
        else
        {
            return parent::render($request, $e);
        }
	}

}
