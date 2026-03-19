<?php namespace App\Http\Controllers;

use App\FeedbackQuestioin;
use App\FeedbackQuestion;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FeedbackQuestionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $outlet_id = Session::get('outlet_session');

        $fb_quest = FeedbackQuestion::where('outlet_id',$outlet_id)->get();

        return view('feedbackquestion.index', array('questions' => $fb_quest));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

        return view('feedbackquestion.create',array('action'=>'add'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

        $outlet_id = Session::get('outlet_session');

        $messages = [
            'question.required' => 'Question is required!',
        ];

        $p = Validator::make($request->all(), [
            'question' => 'required',
        ],$messages);


        if (isset($p) && $p->passes())
        {
            $owner_id = Auth::id();
            $save_continue = Input::get('saveContinue');
            $quest = Input::get('question');

            $qst = new FeedbackQuestion();
            $qst->question= $quest;
            $qst->outlet_id = $outlet_id;
            $qst->created_by = $owner_id;
            $qst->updated_by = $owner_id;
            $result = $qst->save();

            if ( $result ) {
                if ( isset($save_continue) && $save_continue == 'true' ) {
                    return Redirect::route('feedback-question.create')->with('success','New Question has been added....');
                } else {
                    return Redirect::route('feedback-question.index')->with('success','New Question has been added....');
                }

            }

        } else {
            return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
        }
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
        $qst = FeedbackQuestion::find($id);

        return view('feedbackquestion.edit',array('fb_question'=>$qst,'action'=>'edit'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $messages = [
            'question.required' => 'Name is required!',
        ];

        $p = Validator::make(Input::all(), [
            'question' => 'required',
        ],$messages);

        if (isset($p) && $p->passes())
        {
            $owner_id = Auth::id();

            $quest = Input::get('question');
            $outlet_id = Session::get('outlet_session');

            $qst = FeedbackQuestion::find($id);
            $qst->question= $quest;
            $qst->updated_by = $owner_id;
            $result = $qst->save();

            if ( $result ) {
                return Redirect::route('feedback-question.index')->with('success', 'Question has been updated successfully!');
            }

        } else {
            return redirect()->back()->withInput(Input::all())->withErrors($p->errors());
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        FeedbackQuestion::where('id',$id)->delete();
        Session::flash('success', 'Question has been deleted successfully!');
        return Redirect::route('feedback-question.index');
	}

}
