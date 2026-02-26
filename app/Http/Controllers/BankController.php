<?php namespace App\Http\Controllers;

use App\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
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
        $bank->bank_name = Request::get('bank_name');
        $bank->acc_no = Request::get('acc_no');
        $bank->acc_type = Request::get('acc_type');
        $bank->bank_ifsc = Request::get('bank_ifsc');
        $bank->bank_address = Request::get('bank_address');
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