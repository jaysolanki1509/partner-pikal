<?php namespace App\Http\Controllers;

use App\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class BankController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }

    public function index(Request $request)
    {
        $owner_id = Auth::id();
        $banks = Bank::where('bank_master.owner_id',$owner_id)->get();

        return view('bank.index', array('banks' => $banks));
    }

    public function create()
    {
        $owner_id = Auth::id();

        return view('bank.form');
    }

    public function store(){

        $bank = new Bank();
        $bank->owner_id = Auth::id();
        $bank->bank_name = Input::get('bank_name');
        $bank->acc_no = Input::get('acc_no');
        $bank->acc_type = Input::get('acc_type');
        $bank->bank_ifsc = Input::get('bank_ifsc');
        $bank->bank_address = Input::get('bank_address');
        $bank->save();

        return Redirect::route('banks.index')->with('success','New bank has been added');

    }

    public function destroy($id)
    {
        Bank::where('id',$id)->delete();
        Session::flash('error', 'Bank detail has been deleted successfully!');
        return Redirect::to('banks');
    }
}