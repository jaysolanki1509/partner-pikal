<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

class ContactController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
    public function feedback(){
        return view('contact.contact');
    }

    public function getContactUsForm(){

        //Get all the data and store it inside Store Variable
        $data = Input::all();

        //Validation rules
        $rules = array (
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|min:5'
        );

        //Validate data
        $validator = Validator::make ($data, $rules);

        //If everything is correct than run passes.
        if ($validator -> passes()){

            Mail::send('emails.feedback', $data, function($message) use ($data)
            {
                //$message->from($data['email'] , $data['first_name']); uncomment if using first name and email fields
                $message->from('parag@savitriya.com', 'feedback form');
                //email 'To' field: cahnge this to emails that you want to be notified.
                $message->to('parag@savitriya.com', 'Pikal')->subject('feedback form');

            });
            // Redirect to page
            return Redirect::to('/')
                ->with('message', 'Your message has been sent. Thank You!');


            //return View::make('contact');
        }else{
            //return contact form with errors
            return Redirect::to('/')
                ->with('error', 'Feedback must contain more than 5 characters. Try Again.');

        }

    }

}
