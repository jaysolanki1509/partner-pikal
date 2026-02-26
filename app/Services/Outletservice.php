<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 8/1/2015
 * Time: 11:22 AM
 */

namespace App\Services;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;


class Outletservice implements RegistrarContract {
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'user_name' => 'required|max:20',
            'contact_no' => 'required|min:10',
            'email' => 'required|email|unique:users'
            // 'gender'=>'required',
            // 'state'=>'required',
            // 'city'=>'required',



        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Owner
     */

    public function create(array $data)
    {

        return Owner::create
        ([
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'timezone'=>$data['timezone'],
            'password' => bcrypt($data['password']),
            'contact_no'=>$data['contact_no'],
            'state'=>$data['state'],
            'city'=>$data['city'],


        ]);
    }

}