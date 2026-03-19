<?php namespace App\Services;

use App\Account;
use App\Owner;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'account' => 'required|unique:accounts,name',
			'user_name' => 'required|max:20|unique:owners',
            'contact_no' => 'required|min:10|numeric',
			'password' => 'required|min:3|confirmed',
            //'email' => 'required|email|unique:owners'
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

		$account = Account::create([
			'name'=> $data['account']
		]);


		 $owner=Owner::create
        ([
			'account_id' => $account->id,
			'user_name' => $data['user_name'],
			'email' => $data['email'],
            'timezone'=>'123',
            'contact_no'=>$data['contact_no'],
            'password'=>Hash::make($data['password']),
            'gender'=>$data['gender'],
            'state'=>$data['state'],
            'city'=>$data['city'],
            'role_id'=> Role::findRoleByName('superadmin')->id

		]);


       // print_r($owner);exit;
        return $owner->id;
	}

}

