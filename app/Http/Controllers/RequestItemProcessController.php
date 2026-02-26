<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ItemRequest;
//use Illuminate\Http\Request;
use App\Location;
use App\Menu;
use App\Owner;
use App\ResponseDeviation;
use App\Stock;
use App\StockAge;
use App\StockHistory;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RequestItemProcessController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['home']]);
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
//		print_r("Index");exit;

		$user_id=Auth::user()->id;
//		print_r($user_id);exit;

		$item_requests = ItemRequest::getAllRequestedUsersByOwner_toId($user_id);
//		print_r($item_requests);exit;

		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItemProcess.mobileIndex', array('item_requests' => $item_requests));
		} else {
            return view('requestItemProcess.index', array('item_requests' => $item_requests));
		}

//		print_r($item_requests);exit;


	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
//		print_r("create");exit;
		$owner_id = Auth::user()->id;

		$from_date = date('Y-m-d');
		$to_date = date('Y-m-d');
		$selected_user_id = "All";

//		$process = ItemRequest::getTowDatesBetweenItemsByOwnerTo($owner_id, $from_date, $to_date);
		$process = ItemRequest::getNotSetisfiedItemsByOwnerTo($owner_id);

		$item_requests = ItemRequest::join('menus','menus.id','=','item_request.what_item_id')
                                    ->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
                                    ->select('item_request.id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
                                    ->groupBy('item_request.owner_by')
                                    ->where('item_request.owner_to','=',$owner_id)
                                    ->where('item_request.satisfied','=',"No")->get();

        $locations = Location::getLocations($owner_id);
		$locations[''] = 'Select From Location';

		return view('requestItemProcess.create', array('locations'=>$locations,'process' => $process, 'item_requests' => $item_requests,'owner_id' => $owner_id, 'from_date' => $from_date, 'to_date' => $to_date, 'selected_user_id' => $selected_user_id));

		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItemProcess.mobileCreate', array('locations'=>$locations,'process' => $process,'item_requests' => $item_requests, 'owner_id' => $owner_id, 'from_date' => $from_date, 'to_date' => $to_date, 'selected_user_id' => $selected_user_id));
		} else {
			return view('requestItemProcess.create', array('locations'=>$locations,'process' => $process, 'item_requests' => $item_requests,'owner_id' => $owner_id, 'from_date' => $from_date, 'to_date' => $to_date, 'selected_user_id' => $selected_user_id));
		}

//		$today = date('Y-m-d');
//		print_r($today);exit;


//		$item_requests = ItemRequest::getNotSatisfiedItemsOwner_byId($owner_id);

//		$process = ItemRequest::getTodayItemByOwnerTo($owner_id,$today);
//		print_r($process);exit;

//		print_r($item_requests);exit;


	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateRequestItemProcessRequest $request)
	{
		$user_id = Auth::user()->id;
		$input = Request::all();
		$today = $input['satisfied_date']." ".date('H:i:s',time());

		$success = '';
		$error = false;
		//print_r($input);exit;
		$transaction_id = uniqid();
		if ( isset($input['request_id']) && sizeof($input['request_id']) > 0 ) {

			for( $i=0; $i<sizeof($input['request_id']); $i++ ) {

				$request_id = $input['request_id'][$i];
				$satisfy_qty = $total_qty = floatval($input['satisfied_qty'][$i]);
				$loc_id = $input['loc_id'][$i];
				$unit_id = $input['unit_id'][$i];

                $processRequest = ItemRequest::getItemsById($request_id);

                $menu_item = Menu::find($processRequest->what_item_id);
				//continue for nex loop if both field are blank
				if ( $satisfy_qty == '' || $satisfy_qty == 0 ) {

                    $satisfy_qty = 0;
                    $res_dev = new ResponseDeviation();
                    $res_dev->transaction_id = $transaction_id;
                    $res_dev->item_id = $processRequest->what_item_id;
                    $res_dev->item_name	= $menu_item->item;
                    $res_dev->request_qty = $processRequest->qty;
                    $res_dev->request_unit_id = $processRequest->unit_id;
                    $res_dev->satisfied_qty = $satisfy_qty;
                    $res_dev->satisfied_unit_id = $unit_id;
                    $res_dev->for_location_id = $processRequest->location_for;
                    $res_dev->from_location_id = $loc_id;
                    $res_dev->request_by = $processRequest->owner_by;
                    $res_dev->satisfied_by = $user_id;
                    $res_dev->request_when = $processRequest->when;
                    $res_dev->satisfied_when = $today;
                    $res_dev->save();

					continue;
				}

				DB::beginTransaction();
				if ( isset($satisfy_qty) && $satisfy_qty != '') {

					try {

						$other_units = '';
						if( isset($menu_item->secondary_units) && $menu_item->secondary_units != '' ) {
							$units = json_decode($menu_item->secondary_units);
							if ( isset($units) && $units != '' ) {
								foreach( $units as $key=>$u ) {
									if ( $key == $unit_id) {
										$satisfy_qty = floatval($satisfy_qty) * floatval($u);
									}
								}
							}

						}

						$processRequest->price = Menu::getItemIngredPrice($processRequest->what_item_id);
						$processRequest->satisfied_unit_id = $unit_id;
						$processRequest->satisfied = 'Yes';
						$processRequest->type = 'request';
						$processRequest->satisfied_batch_id = $transaction_id;
						$processRequest->satisfied_by = $user_id;
						$processRequest->satisfied_when = $today;
						$processRequest->statisfied_qty = $total_qty;
						$processRequest->location_from = $loc_id;
						$success = $processRequest->save();

						if ( $success ) {

							if ( $processRequest->qty != $satisfy_qty ) {

                                $res_dev = new ResponseDeviation();
                                $res_dev->transaction_id = $transaction_id;
                                $res_dev->item_id = $processRequest->what_item_id;
                                $res_dev->item_name	= $menu_item->item;
                                $res_dev->request_qty = $processRequest->qty;
                                $res_dev->request_unit_id = $processRequest->unit_id;
                                $res_dev->satisfied_qty = $total_qty;
                                $res_dev->satisfied_unit_id = $unit_id;
                                $res_dev->for_location_id = $processRequest->location_for;
                                $res_dev->from_location_id = $loc_id;
                                $res_dev->request_by = $processRequest->owner_by;
                                $res_dev->satisfied_by = $user_id;
                                $res_dev->request_when = $processRequest->when;
                                $res_dev->satisfied_when = $today;
                                $res_dev->save();
                            }

							/*decrease item from location*/
							$from_loc_stock = Stock::where('location_id', $loc_id)
								->where('item_id', $processRequest->what_item_id)
								->first();

							if (isset($from_loc_stock) && sizeof($from_loc_stock) > 0) {

								$remain_qty = $from_loc_stock->quantity - $satisfy_qty;
								$stock = Stock::find($from_loc_stock->id);
								$stock->quantity = $remain_qty;
								$stock->updated_by = $user_id;
								$stock->updated_at = $today;
								$from_loc_result = $stock->save();

							} else {

								$stk_add = new Stock();
								$stk_add->item_id = $processRequest->what_item_id;
								$stk_add->location_id = $loc_id;
								$stk_add->created_by = $user_id;
								$stk_add->updated_by = $user_id;
								$stk_add->quantity = 0 - $satisfy_qty;
								$stk_add->created_at = $today;
								$stk_add->updated_at = $today;
								$from_loc_result = $stk_add->save();
							}

							$for_loc_stock = Stock::where('location_id', $processRequest->location_for)
								->where('item_id', $processRequest->what_item_id)
								->first();

							if (isset($for_loc_stock) && sizeof($for_loc_stock) > 0) {

								$added_qty = $for_loc_stock->quantity + $satisfy_qty;
								$for_loc_stock->quantity = $added_qty;
								$for_loc_stock->updated_by = $user_id;
								$for_loc_stock->updated_at = $today;
								$from_loc_result = $for_loc_stock->save();

							} else {

								$stk_add = new Stock();
								$stk_add->item_id = $processRequest->what_item_id;
								$stk_add->location_id = $processRequest->location_for;
								$stk_add->created_by = $user_id;
								$stk_add->updated_by = $user_id;
								$stk_add->quantity = $satisfy_qty;
								$stk_add->created_at = $today;
								$stk_add->updated_at = $today;
								$from_loc_result = $stk_add->save();
							}


							/*if stock avalilable than decrease quantity*/
							if ( $from_loc_result ) {

								$stock_history = new StockHistory();
								$stock_history->transaction_id = '';
								$stock_history->from_location = $loc_id;
								$stock_history->to_location = $processRequest->location_for;
								$stock_history->item_id = $processRequest->what_item_id;
								$stock_history->type = 'remove';
								$stock_history->quantity = $satisfy_qty;
								$stock_history->reason = 'transfer';
								$stock_history->created_by = $user_id;
								$stock_history->updated_by = $user_id;
								$stock_history->created_at = $today;
								$stock_history->updated_at = $today;
								$result1 = $stock_history->save();

								if ( $result1 ) {

									$stock_history = new StockHistory();
									$stock_history->transaction_id = '';
									$stock_history->from_location = $loc_id;
									$stock_history->to_location = $processRequest->location_for;
									$stock_history->item_id = $processRequest->what_item_id;
									$stock_history->type = 'add';
									$stock_history->quantity = $satisfy_qty;
									$stock_history->reason = 'transfer';
									$stock_history->created_by = $user_id;
									$stock_history->updated_by = $user_id;
									$stock_history->created_at = $today;
									$stock_history->updated_at = $today;
									$result1 = $stock_history->save();

									if ( !$result1 ) {

										$error = true;
										DB::rollBack();
										return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');

									}

								} else {

									$error = true;
									DB::rollBack();
									return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');

								}

									/*$get_stock = StockAge::where('item_id',$processRequest->what_item_id)
										->where('location_id',$loc_id)
										//->where('quantity','>',0)
										->orderby('expiry_date','asc')
										->get();

									if ( isset($get_stock) && sizeof($get_stock) > 0 ) {
										$remain_stk = 0;$first_time = true;
										foreach( $get_stock as $get_stk ) {

											//if stock is less than first batch stock
											if ( $get_stk->quantity > $satisfy_qty && $first_time == true ) {

												$get_stk->quantity = $get_stk->quantity - $satisfy_qty;
												$get_stk->updated_by = $user_id;
												$get_stk->updated_at = $today;
												$get_stk->save();

												$stock_history = new StockHistory();
												$stock_history->transaction_id = $get_stk->transaction_id;
												$stock_history->from_location = $loc_id;
												$stock_history->to_location = $processRequest->location_for;
												$stock_history->item_id = $processRequest->what_item_id;
												$stock_history->type = 'remove';
												$stock_history->quantity = $satisfy_qty;
												$stock_history->reason = 'transfer';
												$stock_history->created_by = $user_id;
												$stock_history->updated_by = $user_id;
												$stock_history->created_at = $today;
												$stock_history->updated_at = $today;
												$result1 = $stock_history->save();

												if ( isset($get_stk->transaction_id) &&  $get_stk->transaction_id != '' ) {

													$add_stock = StockAge::where('item_id',$processRequest->what_item_id)
															->where('location_id',$processRequest->location_for)
															->where('transaction_id',$get_stk->transaction_id)->first();

													if( isset($add_stock) && sizeof($add_stock) > 0 ) {
														$add_stock->quantity = $add_stock->quantity + $satisfy_qty;
														$add_stock->updated_at = $today;
														$add_stock->save();
													} else {

														$add_stock = new StockAge();
														$add_stock->transaction_id = $get_stk->transaction_id;
														$add_stock->item_id = $processRequest->what_item_id;
														$add_stock->location_id = $processRequest->location_for;
														$add_stock->quantity = $satisfy_qty;
														$add_stock->created_at = $today;
														$add_stock->updated_at = $today;
														$add_stock->created_by = $user_id;
														$add_stock->updated_by = $user_id;
														$add_stock->save();

													}
												} else {

													$add_stock = new StockAge();
													$add_stock->item_id = $processRequest->what_item_id;
													$add_stock->location_id = $processRequest->location_for;
													$add_stock->quantity = $satisfy_qty;
													$add_stock->created_at = $today;
													$add_stock->updated_at = $today;
													$add_stock->created_by = $user_id;
													$add_stock->updated_by = $user_id;
													$add_stock->save();

												}

												$stock_history = new StockHistory();
												$stock_history->transaction_id = $get_stk->transaction_id;
												$stock_history->from_location = $loc_id;
												$stock_history->to_location = $processRequest->location_for;
												$stock_history->item_id = $processRequest->what_item_id;
												$stock_history->type = 'add';
												$stock_history->quantity = $satisfy_qty;
												$stock_history->reason = 'transfer';
												$stock_history->created_by = $user_id;
												$stock_history->updated_by = $user_id;
												$stock_history->created_at = $today;
												$stock_history->updated_at = $today;
												$result1 = $stock_history->save();

												break;

											} else {

												if ( $remain_stk > 0 || $first_time == true ) {
													$first_time = false;


													if ( $get_stk->quantity <= $satisfy_qty ) {

														$avail_stock = $get_stk->quantity;
														$satisfy_qty = $satisfy_qty - $get_stk->quantity;
														$remain_stk = $satisfy_qty;

														$get_stk->quantity = $get_stk->quantity - $get_stk->quantity;
														$get_stk->updated_by = $user_id;
														$get_stk->updated_at = $today;
														$get_stk->save();

														if ( isset($get_stk->transaction_id) &&  $get_stk->transaction_id != '' ) {

															$add_stock = StockAge::where('item_id',$processRequest->what_item_id)
																->where('location_id',$processRequest->location_for)
																->where('transaction_id',$get_stk->transaction_id)->first();

															if( isset($add_stock) && sizeof($add_stock) > 0 ) {
																$add_stock->quantity = $add_stock->quantity + $avail_stock;
																$add_stock->updated_at = $today;
																$add_stock->save();
															} else {

																$add_stock = new StockAge();
																$add_stock->transaction_id = $get_stk->transaction_id;
																$add_stock->item_id = $processRequest->what_item_id;
																$add_stock->location_id = $processRequest->location_for;
																$add_stock->quantity = $avail_stock;
																$add_stock->created_at = $today;
																$add_stock->updated_at = $today;
																$add_stock->created_by = $user_id;
																$add_stock->updated_by = $user_id;
																$add_stock->save();

															}

														} else {

															$add_stock = new StockAge();
															$add_stock->item_id = $processRequest->what_item_id;
															$add_stock->location_id = $processRequest->location_for;
															$add_stock->quantity = $avail_stock;
															$add_stock->created_at = $today;
															$add_stock->updated_at = $today;
															$add_stock->created_by = $user_id;
															$add_stock->updated_by = $user_id;
															$add_stock->save();

														}

														$stock_history = new StockHistory();
														$stock_history->transaction_id = $get_stk->transaction_id;
														$stock_history->from_location = $loc_id;
														$stock_history->to_location = $processRequest->location_for;
														$stock_history->item_id = $processRequest->what_item_id;
														$stock_history->type = 'remove';
														$stock_history->quantity = $avail_stock;
														$stock_history->reason = 'transfer';
														$stock_history->created_by = $user_id;
														$stock_history->updated_by = $user_id;
														$stock_history->created_at = $today;
														$stock_history->updated_at = $today;
														$result1 = $stock_history->save();

														if ( $result1 ) {

															$stock_history1 = new StockHistory();
															$stock_history1->transaction_id = $get_stk->transaction_id;
															$stock_history1->from_location = $loc_id;
															$stock_history1->to_location = $processRequest->location_for;
															$stock_history1->item_id = $processRequest->what_item_id;
															$stock_history1->type = 'add';
															$stock_history1->quantity = $avail_stock;
															$stock_history1->reason = 'transfer';
															$stock_history1->created_by = $user_id;
															$stock_history1->updated_by = $user_id;
															$stock_history1->created_at = $today;
															$stock_history1->updated_at = $today;
															$result2 = $stock_history1->save();

														} else {
															$error = true;
															DB::rollBack();
															return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input1');
														}

													} else {

														$get_stk->quantity = $get_stk->quantity - $satisfy_qty;
														$get_stk->updated_by = $user_id;
														$get_stk->updated_at = $today;
														$get_stk->save();

														if ( isset($get_stk->transaction_id) &&  $get_stk->transaction_id != '' ) {

															$add_stock = StockAge::where('item_id',$processRequest->what_item_id)
																->where('location_id',$processRequest->location_for)
																->where('transaction_id',$get_stk->transaction_id)->first();

															if( isset($add_stock) && sizeof($add_stock) > 0 ) {
																$add_stock->quantity = $add_stock->quantity + $satisfy_qty;
																$add_stock->updated_at = $today;
																$add_stock->save();
															} else {

																$add_stock = new StockAge();
																$add_stock->item_id = $processRequest->what_item_id;
																$add_stock->location_id = $processRequest->location_for;
																$add_stock->quantity = $satisfy_qty;
																$add_stock->created_at = $today;
																$add_stock->updated_at = $today;
																$add_stock->created_by = $user_id;
																$add_stock->updated_by = $user_id;
																$add_stock->save();
															}

														} else {

															$add_stock = new StockAge();
															$add_stock->item_id = $processRequest->what_item_id;
															$add_stock->location_id = $processRequest->location_for;
															$add_stock->quantity = $satisfy_qty;
															$add_stock->created_at = $today;
															$add_stock->updated_at = $today;
															$add_stock->created_by = $user_id;
															$add_stock->updated_by = $user_id;
															$add_stock->save();

														}

														$stock_history = new StockHistory();
														$stock_history->transaction_id = $get_stk->transaction_id;
														$stock_history->from_location = $loc_id;
														$stock_history->to_location = $processRequest->location_for;
														$stock_history->item_id = $processRequest->what_item_id;
														$stock_history->type = 'remove';
														$stock_history->quantity = $satisfy_qty;
														$stock_history->reason = 'transfer';
														$stock_history->created_by = $user_id;
														$stock_history->updated_by = $user_id;
														$stock_history->created_at = $today;
														$stock_history->updated_at = $today;
														$result1 = $stock_history->save();

														if ( $result1 ) {

															$stock_history1 = new StockHistory();
															$stock_history1->transaction_id = $get_stk->transaction_id;
															$stock_history1->from_location = $loc_id;
															$stock_history1->to_location = $processRequest->location_for;
															$stock_history1->item_id = $processRequest->what_item_id;
															$stock_history1->type = 'add';
															$stock_history1->quantity = $satisfy_qty;
															$stock_history1->reason = 'transfer';
															$stock_history1->created_by = $user_id;
															$stock_history1->updated_by = $user_id;
															$stock_history1->created_at = $today;
															$stock_history1->updated_at = $today;
															$result2 = $stock_history1->save();

														} else {
															$error = true;
															DB::rollBack();
															return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input2');
														}

														break;
													}

													if ( $satisfy_qty <= 0 ) {
														break;
													}

												}
											}

										}

									} else {

										$st_age_add = new StockAge();
										$st_age_add->location_id = $loc_id;
										$st_age_add->item_id = $processRequest->what_item_id;
										$st_age_add->transaction_id = '';
										$st_age_add->quantity = 0 - $satisfy_qty;
										$st_age_add->created_by = $user_id;
										$st_age_add->updated_by = $user_id;
										$st_age_add->created_at = $today;
										$st_age_add->updated_at = $today;
										$st_age_result = $st_age_add->save();

										if ( $st_age_result) {

											$st_age_add1 = new StockAge();
											$st_age_add1->location_id = $processRequest->location_for;
											$st_age_add1->item_id = $processRequest->what_item_id;
											$st_age_add1->transaction_id = '';
											$st_age_add1->quantity = $satisfy_qty;
											$st_age_add1->created_by = $user_id;
											$st_age_add1->updated_by = $user_id;
											$st_age_add1->created_at = $today;
											$st_age_add1->updated_at = $today;
											$st_age_result1 = $st_age_add1->save();

											if ( $st_age_result1) {

												$stock_history = new StockHistory();
												$stock_history->from_location = $loc_id;
												$stock_history->to_location = $processRequest->location_for;
												$stock_history->item_id = $processRequest->what_item_id;
												$stock_history->type = 'remove';
												$stock_history->quantity = $satisfy_qty;
												$stock_history->reason = 'transfer';
												$stock_history->created_by = $user_id;
												$stock_history->updated_by = $user_id;
												$stock_history->created_at = $today;
												$stock_history->updated_at = $today;
												$result1 = $stock_history->save();

												if ( $result1 ){

													$stock_history1 = new StockHistory();
													$stock_history1->from_location = $loc_id;
													$stock_history1->to_location = $processRequest->location_for;
													$stock_history1->item_id = $processRequest->what_item_id;
													$stock_history1->type = 'add';
													$stock_history1->quantity = $satisfy_qty;
													$stock_history1->reason = 'transfer';
													$stock_history1->created_by = $user_id;
													$stock_history1->updated_by = $user_id;
													$stock_history1->created_at = $today;
													$stock_history1->updated_at = $today;
													$result1 = $stock_history1->save();

													if ( !$result1 ) {
														$error = true;
														DB::rollBack();
														return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');

													}

												} else {

													$error = true;
													DB::rollBack();
													return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');
												}

											} else {

												$error = true;
												DB::rollBack();
												return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');


											}

										} else {
											$error = true;
											DB::rollBack();
											return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');

										}
									}*/

							} else {

								$error = true;
								DB::rollBack();
								return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');
							}

						} else {
							$error = true;
							DB::rollBack();
							return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');
						}



					} catch( \Exception $e ) {
						$error = true;
						DB::rollBack();
						return redirect()->back()->withInput(Request::all())->with('error', $e->getMessage());
					}

				}
				DB::commit();
			}
			if( $error == false ) {

				return Redirect('/requestItemProcess')->with('success', 'Request processed successfully ');

				if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
					DB::commit();
					return Redirect('/requestItemProcess')->with('success', 'Request processed successfully ');
				} else {
					DB::commit();
					return Redirect('/requestItemProcess')->with('success', 'Request processed successfully ');
				}

			} else {

				DB::rollBack();
				return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');

				if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
					DB::rollBack();
					return Redirect('/requestItemProcess/create')->with('error', 'Something Wrong In Input ');
				} else {
					DB::rollBack();
					return Redirect('/requestItemProcess/create')->with('error', 'Something Wrong In Input ');
				}

			}

		} else {

			DB::rollBack();
			return redirect()->back()->withInput(Request::all())->with('error','Something Wrong In Input');

			if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
				DB::rollBack();
				return Redirect('/requestItemProcess/create')->with('error', 'Something Wrong In Input ');
			} else {
				DB::rollBack();
				return Redirect('/requestItemProcess/create')->with('error', 'Something Wrong In Input ');
			}
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
		return view('requestItemProcess.show');
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
		return view('requestItemProcess.edit');
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
		return view('requestItemProcess.edit');
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
		return view('requestItemProcess.index');
	}

	public function setisfiedResponse() {

		$input = Request::all();
		$owner_id = Auth::id();

		if ( isset($input) && sizeof($input) > 0 ) {

			$input = Request::all();
			$response = array();

			$search = $input['sSearch'];

			$sort = $input['sSortDir_0'];
			$sortCol=$input['iSortCol_0'];
			$sortColName=$input['mDataProp_'.$sortCol];

			$sort_field = 'stocks.updated_at';
			//echo $sort_field;exit;
			//sort by column

			$total_colomns = $input['iColumns'];
			if ( $sortColName == "transaction_id" ) {
				$sort_field = 'item_request.satisfied_batch_id';
			} elseif ( $sortColName == "date" ) {
				$sort_field = 'item_request.satisfied_when';
			} elseif ( $sortColName == "for_location" ) {
				$sort_field = 'location';
			} else {
				$sort_field = 'item_request.satisfied_when';
				$sort = 'DESC';
			}
			$search_col = '';$query_filter = '';

			for ( $j=0; $j<=$total_colomns-1; $j++ ) {

				if ( $j == 0 )continue;

				if ( isset($input['sSearch_'.$j]) && $input['sSearch_'.$j] != '' ) {

					$search = $input['sSearch_'.$j];
					$searchColName = $input['mDataProp_'.$j];
					//echo $searchColName;exit();

					if ( $searchColName == 'transaction_id' ) {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND item_request.satisfied_batch_id like '%$search%'";
						} else {
							$search_col = "item_request.satisfied_batch_id like '%$search%'";
						}

					} else if ( $searchColName == 'for_location' ) {

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND l.id = '$search'";
						} else {
							$search_col = "l.id = '$search'";
						}

					} else if ( $searchColName ==  'date' ) {
						//echo 'here';exit;
						$from = $search." 00:00:00";
						$to = $search." 23:59:59";

						if ( isset($search_col) && $search_col != '' ) {
							$search_col .= " AND item_request.satisfied_when BETWEEN '$from' AND '$to'";
						} else {
							$search_col = "item_request.satisfied_when BETWEEN '$from' AND '$to'";
						}

					}

				}

			}

			//echo $search_col;exit;

			if ( $search_col == '')$search_col = '1=1';
			$where = "item_request.satisfied = 'Yes' AND item_request.owner_to = $owner_id AND item_request.satisfied_batch_id != '' AND ";

			$total_count = ItemRequest::join('locations as l','l.id','=','item_request.location_for')
										->select('item_request.*','l.name as location')
										->whereRaw(" $where ($search_col)")
										->groupBy('item_request.satisfied_batch_id')
										->get();

            $total_records = sizeof($total_count);

            $stock_result = ItemRequest::join('locations as l','l.id','=','item_request.location_for')
										->select('item_request.*','l.name as location')
										->whereRaw(" $where ($search_col)")
										->groupBy('item_request.satisfied_batch_id')
										->take($input['iDisplayLength'])
										->skip($input['iDisplayStart'])
										->orderBy($sort_field, $sort)->get();


			if ( $total_records > 0 ) {

				$i = 0;
				foreach ( $stock_result as $stock ) {

					$response['result'][$i]['DT_RowId'] = $stock->satisfied_batch_id;
					$response['result'][$i]['check_col'] = "";
					$response['result'][$i]['transaction_id'] = $stock->satisfied_batch_id;
					$response['result'][$i]['for_location'] = $stock->location;
					$response['result'][$i]['date'] = $stock->satisfied_when;
					$i++;
				}


			} else {
				$total_records = 0;
				$response['result'] = array();
			}

			$response['iTotalRecords'] = $total_records;
			$response['iTotalDisplayRecords'] = $total_records;
			$response['aaData'] = $response['result'];
            $locations = Location::where('created_by',$owner_id)->get();
            $response['locations'] = $locations;
			return json_encode($response);

		}

		return view('requestItemProcess.setisfiedResponse');

	}

	public function satisfiedResponselist() {

		$id = Request::get('id');
		$data = array();

		$request = ItemRequest::join('unit as u','u.id','=','item_request.satisfied_unit_id')
							->join('unit as u1','u1.id','=','item_request.unit_id')
							->join('locations as for','for.id','=','item_request.location_for')
							->join('locations as from','from.id','=','item_request.location_from')
							->join('owners as o','o.id','=','item_request.owner_to')
							->select('item_request.*','u1.name as req_unit_name','u.name as unit_name','for.name as for_location','from.name as from_location','o.user_name as user')
							->where('item_request.satisfied_batch_id',$id)
							->get();

		if ( isset($request) && sizeof($request) > 0 ) {
			$i=0;
			foreach( $request as $req ) {

				$data[$i]['id'] = $req->id;
				$data[$i]['item'] = $req->what_item;
				$data[$i]['req_qty'] = $req->qty." ".$req->req_unit_name;
				$data[$i]['sat_qty'] = $req->statisfied_qty." ".$req->unit_name;
				$data[$i]['for'] = $req->for_location;
				$data[$i]['from'] = $req->from_location;
				$data[$i]['user'] = $req->user;
				$data[$i]['when'] = $req->when;
				$data[$i]['satisfied_batch_id'] = $req->satisfied_batch_id;
				$i++;

			}
		}

		return view('requestItemProcess.satisfiedResponseList',array('data'=>$data));

	}

	public function getRequestedItems()
	{
//		$date = Request::get("date");
//		print_r($date);exit;

		$from_date = Request::get("from_date");
		$to_date = Request::get("to_date");
		$selected_user_id = Request::get("selected_user_id");
//		print_r($selected_user_id);exit;

//		print_r($from_date);
//		print_r($to_date);exit;
		if ($to_date < $from_date){
			return Redirect('/requestItemProcess/create')->with('error', 'ToDate must be greaterthan FromDate.');
		}

		$owner_id = Auth::user()->id;

		if($selected_user_id == "All") {
			$items = ItemRequest::getTowDatesBetweenItemsByOwnerTo($owner_id, $from_date, $to_date);
		} else {
			$items = ItemRequest::getTowDatesBetweenSelectedUserItemsByOwnerTo($owner_id, $selected_user_id , $from_date, $to_date);
		}

//		print_r($items);exit;

//		$item_requests = ItemRequest::getNotSatisfiedItemsOwner_byId($owner_id);

//		$items = ItemRequest::getTodayItemByOwnerTo($owner_id,$date);

//		$process['date']=$date;
		$process['from_date']=$from_date;
		$process['to_date']=$to_date;
		$process['selected_user_id']=$selected_user_id;
		$process['process']=$items;

		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItemProcess.mobileCreate', $process);
		} else {
			return view('requestItemProcess.create', $process);
		}
	}

	public function getRequestedItem($id)
	{
//		print_r($id);exit;
		$owner_id = Auth::user()->id;
		$from_date = date('Y-m-d');
		$to_date = date('Y-m-d');

		$selected_user_id = $id;
//		$items = ItemRequest::getTowDatesBetweenSelectedUserItemsByOwnerTo($owner_id, $selected_user_id , $from_date, $to_date);
		$items = ItemRequest::getSelectedUserNotSetisfiedItemsByOwnerTo($owner_id, $selected_user_id);
//		print_r($items);exit;

		$item_requests = DB::table("item_request")
			->leftJoin('menus','menus.id','=','item_request.what_item_id')
			->leftJoin('menu_titles','menu_titles.id','=','menus.menu_title_id')
			->select('item_request.id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id', 'menus.menu_title_id', 'menus.item', 'menu_titles.title')
			->groupBy('item_request.owner_by')
			->where('item_request.owner_to','=',$owner_id)
			->where('item_request.owner_by','=',$selected_user_id)
			->where('item_request.satisfied','=',"No")->get();

		$admin = Owner::menuOwner();

		$locations = Location::getLocations($admin);
		$locations[''] = 'Select From Location';
		//		$item_requests = ItemRequest::getNotSatisfiedItemsOwner_byId($owner_id);

//		$items = ItemRequest::getTodayItemByOwnerTo($owner_id,$date);

//		$process['date']=$date;
		$process['from_date']=$from_date;
		$process['to_date']=$to_date;
		$process['process']=$items;
		$process['selected_user_id']=$selected_user_id;

		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItemProcess.mobileCreate', array('locations'=>$locations,'process' => $process,'item_requests' => $item_requests, 'owner_id' => $owner_id, 'from_date' => $from_date, 'to_date' => $to_date, 'selected_user_id' => $selected_user_id));
		} else {
			return view('requestItemProcess.create', array('locations'=>$locations,'process' => $process,'item_requests' => $item_requests, 'owner_id' => $owner_id, 'from_date' => $from_date, 'to_date' => $to_date, 'selected_user_id' => $selected_user_id));
		}

	}

	public function getResponseItemEdit($id)
	{
		$owner_id = Auth::user()->id;

		//$items = ItemRequest::getSelectedUserNotSetisfiedItemsByOwnerTo($owner_id, $selected_user_id);
        $getRequest = ItemRequest::find($id);
        $menuItem = Menu::find($getRequest->what_item_id);
        if(isset($menuItem) && sizeof($menuItem)>0) {
            $items = Menu::getItemsQuanityonLocation($menuItem->menu_title_id,$getRequest->location_for);
        }else{
            return Redirect('/responseItems/setisfiedResponse')->with('error', 'error - Some Item not found');
        }

        $requested_user_by_id = $getRequest->owner_by;
        $requested_user_to_id = $getRequest->owner_to;
        $location_from = $getRequest->location_from;
        $location_for = $getRequest->location_for;
        $requested_date = $getRequest->when;
        $responsed_user_id = $getRequest->satisfied_by;
        $responsed_date = $getRequest->satisfied_when;
        $responsed_time = Carbon::parse($getRequest->satisfied_when)->toTimeString();
		$admin = Owner::menuOwner();

		$locations = Location::getLocations($admin);
		$locations[''] = 'Select Location';

        $users = Owner::where('created_by', $admin)->orWhere('id',$admin)->get();

        $units = Unit::all()->pluck('name','id');
        $units[''] = 'Select unit';

        $owners = ['' => 'Select User'];
        if ( isset($users) && sizeof($users) > 0 ) {
            foreach( $users as $u ) {
                $owners[$u->id] = $u->user_name;
                $user_arr[] = $u->id;
            }
        }
//		$process['date']=$date;
		$process['req_date']=Carbon::parse($requested_date)->format('Y-m-d');
		$process['res_date']=Carbon::parse($responsed_date)->format('Y-m-d');
		$process['res_time']=$responsed_time;
        $process['req_qty']=$getRequest->qty;
        $process['res_qty']=$getRequest->statisfied_qty;
		$process['items']=$items;
		$process['price']=$getRequest->price;
		$process['requested_user_to_id']=$requested_user_to_id;
		$process['requested_user_by_id']=$requested_user_by_id;
		$process['responsed_user_id']=$responsed_user_id;

        return view('requestItemProcess.edit', array('request_id'=>$id,'owners'=>$owners,
                                                    'items'=>$items,
                                                    'location_from'=>$location_from,'location_for'=>$location_for,
                                                    'selected_item'=>$getRequest->what_item_id,'units'=>$units,
                                                    'req_unit'=>$getRequest->unit_id,'res_unit'=>$getRequest->satisfied_unit_id,
                                                    'locations'=>$locations,'process' => $process));

	}

	public function getResponseItemDelete(){
	    $request_id = Request::get("id");

        $requsest = ItemRequest::find($request_id);

        if(isset($requsest) && sizeof($requsest)>0){

            $requsest->delete();
            $result = "success";
            return $result;

        }else{
            $result = "error";
            return $result;
        }


    }

	public function responseUpdate(){


        $req_id = Request::get('request_id');
        $item_id = Request::get('item_id');
        $menu_item = Menu::find($item_id);

        $reqObj = ItemRequest::find($req_id);
        $reqObj->what_item_id  = $item_id;
        $reqObj->what_item = $menu_item->item;
        $reqObj->unit_id = Request::get('req_unit');
        $reqObj->price = Request::get('price');
        $reqObj->owner_to = Request::get('req_owner_to_id');
        $reqObj->owner_by = Request::get('req_owner_by_id');
        $reqObj->location_for = Request::get('req_location_id');
        $reqObj->when = Request::get('requested_date');
        $reqObj->qty = Request::get('requested_qty');
        $reqObj->satisfied_by = Request::get('res_owner_id');
        $reqObj->satisfied_when = Request::get('satisfied_date').' '.Request::get('time');
        $reqObj->statisfied_qty = Request::get('responsed_qty');
        $reqObj->satisfied_unit_id = Request::get('res_unit');
        $reqObj->location_from = Request::get('res_location_id');
        $result = $reqObj->save();

        if($result){
            return Redirect::route('requestItemProcess.setisfiedResponse')->with('success', 'Purchase information updated successfully!');
        }


    }

	public function getSatisfiedItemTime()
	{
		$user_id=Auth::user()->id;
//		print_r("here");exit;
		$today = date('Y-m-d');
//		print_r($today);exit;
		$satisfied_item = DB::table("item_request")
			->groupBy('updated_at')
			->where('owner_to','=',$user_id)
			->where('satisfied','=','Yes')
			->where('satisfied_when','=',$today)->get();
//		print_r($satisfied_item);exit;

		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItemProcess.mobileSelectDate', array('satisfied_items' => $satisfied_item));
		} else {
			return view('requestItemProcess.selectDate', array('satisfied_items' => $satisfied_item));
		}

	}

	public function getSatisfiedUser($selected_time)
	{
		$user_id=Auth::user()->id;
//		print_r($selected_time);exit;
		$users = DB::table("item_request")
//			->distinct()
//			->select('owner_by')
			->groupBy('owner_by')
			->where('owner_to','=',$user_id)
			->where('satisfied','=','Yes')
			->where('updated_at','=',$selected_time)->get();

//		print_r($users);exit;
		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItemProcess.mobileSelectUsers', array('users' => $users, 'selected_time' => $selected_time));
		} else {
			return view('requestItemProcess.selectUsers', array('users' => $users, 'selected_time' => $selected_time));
		}

	}

	public function getSatisfiedCategory($id,$time)
	{
//		print_r("here");exit;

		$owner_to=Auth::user()->id;
		$selected_user_id = $id;
		$selected_time = $time;
//		print_r($selected_user_id);exit;

		if($id == "All"){
//			print_r("here");exit;
			$categories = DB::table("item_request")
				->join('menus','menus.id','=','item_request.what_item_id')
				->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
				->select('item_request.id as request_id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
				->groupBy('menu_titles.id')
				->where('item_request.owner_to','=',$owner_to)
				->where('item_request.updated_at','=',$selected_time)
				->where('item_request.satisfied','=',"Yes")->get();
//			print_r($categories);exit;
		} else {
			$categories = DB::table("item_request")
				->join('menus','menus.id','=','item_request.what_item_id')
				->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
				->select('item_request.id as request_id', 'item_request.what_item_id', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
				->groupBy('menu_titles.id')
				->where('item_request.owner_to','=',$owner_to)
				->where('item_request.owner_by','=',$selected_user_id)
				->where('item_request.updated_at','=',$selected_time)
				->where('item_request.satisfied','=',"Yes")->get();
		}



//		print_r($users);exit;
		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItemProcess.mobileSelectCategory', array('categories' => $categories, 'selected_user_id' => $selected_user_id, 'selected_time' => $selected_time));
		} else {
			return view('requestItemProcess.selectCategory', array('categories' => $categories, 'selected_user_id' => $selected_user_id, 'selected_time' => $selected_time));
		}

	}

	public function getSatisfiedItem($id,$time,$cate_id)
	{
//		print_r("here");exit;

		$owner_to=Auth::user()->id;
		$selected_user_id = $id;
		$selected_time = $time;
		$selected_cate_id = $cate_id;

		//print_r($selected_cate_id);exit;
		if($selected_user_id == "All"){

			if($cate_id == "All"){

				$satisfied_item = DB::table("item_request")
					->join('menus','menus.id','=','item_request.what_item_id')
					->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
					->select('item_request.id as request_id', 'item_request.what_item_id', 'item_request.what_item','item_request.statisfied_qty as statisfied_qty', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
					->groupBy('item_request.owner_by')
					->where('item_request.owner_to','=',$owner_to)
					->where('item_request.updated_at','=',$selected_time)
					->where('item_request.satisfied','=',"Yes")->get();

			} else {

				$satisfied_item = DB::table("item_request")
					->join('menus','menus.id','=','item_request.what_item_id')
					->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
					->select('item_request.id as request_id', 'item_request.what_item_id','item_request.statisfied_qty as statisfied_qty', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
					->groupBy('item_request.owner_by')
					->where('item_request.owner_to','=',$owner_to)
					->where('menu_titles.id','=',$selected_cate_id)
					->where('item_request.updated_at','=',$selected_time)
					->where('item_request.satisfied','=',"Yes")->get();

			}

		} else {

			if($cate_id == "All"){

				$satisfied_item = DB::table("item_request")
					->join('menus','menus.id','=','item_request.what_item_id')
					->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
					->select('item_request.id as request_id', 'item_request.what_item_id', 'item_request.what_item','item_request.statisfied_qty as statisfied_qty', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
					->groupBy('menu_titles.id')
					->where('item_request.owner_to','=',$owner_to)
					->where('item_request.owner_by','=',$selected_user_id)
					->where('item_request.updated_at','=',$selected_time)
					->where('item_request.satisfied','=',"Yes")->get();

			} else {

				$satisfied_item = DB::table("item_request")
					->join('menus','menus.id','=','item_request.what_item_id')
					->join('menu_titles','menu_titles.id','=','menus.menu_title_id')
					->select('item_request.id as request_id', 'item_request.what_item_id','item_request.statisfied_qty as statisfied_qty', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
					->groupBy('menu_titles.id')
					->where('item_request.owner_to','=',$owner_to)
					->where('item_request.owner_by','=',$selected_user_id)
					->where('menu_titles.id','=',$selected_cate_id)
					->where('item_request.updated_at','=',$selected_time)
					->where('item_request.satisfied','=',"Yes")->get();

			}

		}

        $satisfied_item_process=DB::table("item_request")
            ->leftJoin('menus','menus.id','=','item_request.what_item_id')
            ->leftJoin('menu_titles','menu_titles.id','=','menus.menu_title_id')
            ->leftJoin('owners','item_request.owner_by','=','owners.id')
            ->select('owners.user_name','item_request.id as request_id', 'item_request.what_item_id','item_request.statisfied_qty as statisfied_qty', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'menus.id as item_id', 'menus.item', 'menu_titles.id as cate_id' , 'menu_titles.title')
            ->where('item_request.owner_to','=',$owner_to)
            ->where('item_request.updated_at','=',$selected_time)
            ->where('item_request.satisfied','=',"Yes")
            ->orderBy('item_request.owner_by', 'desc')
            ->orderBy('cate_id', 'desc');
            //->groupBy('categories.id');

        //print_r($satisfied_item_process->get());exit;
        if(strcmp($selected_user_id,'All')!=0){
            $satisfied_item_process=$satisfied_item_process->where('item_request.owner_by','=',$selected_user_id);
        }
        if(strcmp($selected_cate_id,'All')!=0){
            $satisfied_item_process=$satisfied_item_process->where('menu_titles.id','=',$selected_cate_id);
        }
        if(strcmp($selected_cate_id,'All')==0 && strcmp($selected_user_id,'All')==0){
            $satisfied_item_process=$satisfied_item_process;
        }
        $satisfied_item_process=$satisfied_item_process->get();
        /*$processed_data = [] ;
        $i=0;
        foreach($satisfied_item_process as $satisfied_item_processes){
            $table_data=DB::table("item_request")
                ->leftJoin('item_master','item_master.id','=','item_request.what_item_id')
                ->leftJoin('categories','categories.id','=','item_master.catagory_id')
                ->leftJoin('owners','item_request.owner_by','=','owners.id')
                ->select('owners.user_name','item_request.id as request_id', 'item_request.what_item_id','item_request.statisfied_qty as statisfied_qty', 'item_request.what_item', 'item_request.owner_to','item_request.owner_by','item_request.when', 'item_request.qty', 'item_request.existing_qty', 'item_master.id as item_id', 'item_master.item_name', 'categories.id as cate_id' , 'categories.title')
                ->where('item_request.owner_to','=',$owner_to)
                ->where('item_request.updated_at','=',$selected_time)
                ->where('item_request.satisfied','=',"Yes")
                ->where('item_request.owner_by','=',$satisfied_item_processes->owner_by)->get();

            array_push($processed_data,$table_data);
            $i++;
        }*/
        //exit;

        $d = array('data_table' => $satisfied_item_process, 'check_print'=>'check_response');
        $json_data = json_encode($d);


		//print_r($satisfied_item);exit;

//		print_r($users);exit;
		if( preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])){
			return view('requestItemProcess.mobileSatisfiedItems', array('print_data'=>$json_data,'satisfied_items' => $satisfied_item, 'selected_user_id' => $selected_user_id, 'selected_cate_id' => $selected_cate_id,'selected_time' => $selected_time, 'owner_to' => $owner_to));
		} else {
			//return view('requestItemProcess.mobileSatisfiedItems', array('print_data'=>$json_data,'satisfied_items' => $satisfied_item, 'selected_user_id' => $selected_user_id, 'selected_cate_id' => $selected_cate_id,'selected_time' => $selected_time, 'owner_to' => $owner_to));
			return view('requestItemProcess.satisfiedItems', array('print_data'=>$json_data,'satisfied_items' => $satisfied_item, 'selected_user_id' => $selected_user_id, 'selected_cate_id' => $selected_cate_id,'selected_time' => $selected_time, 'owner_to' => $owner_to));
		}

	}

	public function requestProcessItemStockDetail() {

		$owner_id = Auth::id();
		$admin = Owner::menuOwner();

		$item_id = Request::get('item_id');
		$item_name = Request::get('item_name');
		$req_qty = Request::get('req_qty');
		$req_id = Request::get('req_id');
		$flag = Request::get('flag');

		if ( $flag == 'open') {

			$locations = Location::where('locations.created_by',$admin)->get();

            $item_qty = Stock::where('item_id',$item_id)->select('location_id','quantity')->get();
            $loc_qty_arr = array();
            $unit_id = Menu::find($item_id)->unit_id;
            $unit = Unit::getUnitbyId($unit_id)->name;
            foreach ($item_qty as $loc_qty){
                $qty = isset($loc_qty->quantity)?$loc_qty->quantity:'0';
                $unit = isset($unit)?$unit:'';
                $loc_qty_arr[$loc_qty->location_id] = $qty.' '.$unit;
            }

			return view('requestItemProcess.requestProcessStockDetail',array('stock'=>$loc_qty_arr,'req_id'=>$req_id,'flag'=>$flag,'locations'=>$locations,'item_name'=>$item_name,'req_qty'=>$req_qty));

		} else if( $flag == 'stock') {
		    $loc_id = Request::get('location_id');

			$stock_detail = StockAge::where('location_id',$loc_id)->where('item_id',$item_id)->where('quantity','>',0)->get();
			return view('requestItemProcess.stockAgeViewList',array('stock'=>$stock_detail,'flag'=>$flag));

		} else if( $flag == 'process' ) {

			$transaction_id = Request::get('transaction_id');
			$satisfy_qty = Request::get('satisfy_qty');
			//$satisfy_when = Request::get('satisfy_date');
			$response = array();
            $loc_id = Request::get('location_id');
			$total_qty = 0;
			$today = date('Y-m-d');
			DB::beginTransaction();
			if ( isset($transaction_id) && sizeof($transaction_id) > 0 ) {

				$processRequest = ItemRequest::getItemsById($req_id);

				/*foreach( $transaction_id as $key=>$trans ) {

					if ( isset($satisfy_qty[$key]) && $satisfy_qty[$key] > 0 ) {

						$check_stock = StockAge::where('location_id',$loc_id)->where('item_id',$item_id)->where('transaction_id',$trans)->first();
						if ( isset($check_stock) && sizeof($check_stock) > 0 ) {
							$qty = floatval($check_stock->quantity) - floatval($satisfy_qty[$key]);
							$total_qty += $satisfy_qty[$key];
							$result = StockAge::where('id',$check_stock->id)->update(['quantity'=>$qty]);

							if ( $result ) {

								$unit_id = Unit::where('id',$check_stock->unit_id)->first();

								$location_for_stock = StockAge::where('location_id',$processRequest->location_for)->where('item_id',$item_id)->where('transaction_id',$trans)->first();

								if ( isset($location_for_stock) && sizeof($location_for_stock) > 0 ) {

									$location_for_stock->quantity = $location_for_stock->quantity + $satisfy_qty[$key];
									//$location_for_stock->updated_at = $satisfy_when." 00:00:00";
									$location_for_stock->updated_by = $owner_id;
									$result1 = $location_for_stock->save();

								} else {

									$st_age_add = new StockAge();
									$st_age_add->location_id = $processRequest->location_for;
									$st_age_add->item_id = $item_id;
									$st_age_add->unit_id = $unit_id->id;
									$st_age_add->transaction_id = $trans;
									$st_age_add->expiry_date = $check_stock->expiry_date;
									$st_age_add->quantity = $satisfy_qty[$key];
									$st_age_add->created_by = $owner_id;
									$st_age_add->updated_by = $owner_id;
									//$st_age_add->created_at = $satisfy_when." 00:00:00";
									//$st_age_add->updated_at = $satisfy_when." 00:00:00";
									$result1 = $st_age_add->save();

								}

								if ( !$result1 ) {

									DB::rollBack();
									$response['status'] = 'error';
									$response['msg'] = 'Stock record not updated';
									return json_encode($response);

								}


							} else {

								DB::rollBack();
								$response['status'] = 'error';
								$response['msg'] = 'Stock record not updated';
								return json_encode($response);

							}

						} else {
							DB::rollBack();
							$response['status'] = 'error';
							$response['msg'] = 'Stock not available';
							return json_encode($response);
						}

					}

				}*/

                if (true) {

                    $from_loc_stock = Stock::where('location_id', $loc_id)
                        ->where('item_id', $item_id)
                        ->first();

                    if (isset($from_loc_stock) && sizeof($from_loc_stock) > 0) {

                        $unit_id = Unit::where('id',Menu::find($from_loc_stock->item_id)->unit_id)->first();

                        $remain_qty = floatval($from_loc_stock->quantity) - floatval($total_qty);
                        $stock = Stock::find($from_loc_stock->id);
                        $stock->quantity = $remain_qty;
                        $stock->updated_by = $owner_id;
                        $from_loc_result = $stock->save();

                        if ( $from_loc_result ) {

                            foreach( $transaction_id as $key=>$trans ) {

                                if ( isset($satisfy_qty[$key]) && $satisfy_qty[$key] > 0 ) {

                                    $stk_hist_rem = new StockHistory();
                                    $stk_hist_rem->transaction_id = $trans;
                                    $stk_hist_rem->from_location = $processRequest->location_from;
                                    $stk_hist_rem->to_location = $processRequest->location_for;
                                    $stk_hist_rem->item_id = $processRequest->what_item_id;
                                    $stk_hist_rem->quantity = $satisfy_qty[$key];
                                    $stk_hist_rem->type = 'remove';
                                    $stk_hist_rem->created_by = $owner_id;
                                    $stk_hist_rem->updated_by = $owner_id;
                                    //$stk_hist_rem->created_at = $satisfy_when." 00:00:00";
                                    //$stk_hist_rem->updated_at = $satisfy_when." 00:00:00";
                                    $stk_hist_rem_result = $stk_hist_rem->save();

                                    if ( !$stk_hist_rem_result ) {

                                        DB::rollBack();
                                        $response['status'] = 'error';
                                        $response['msg'] = 'Stock record not updated';
                                        return json_encode($response);
                                    }

                                }

                            }

                            $for_loc_stock = Stock::where('location_id', $processRequest->location_for)
                                ->where('item_id', $processRequest->what_item_id)
                                ->first();

                            if ( isset($for_loc_stock) && sizeof($for_loc_stock) >  0 ) {

                                $new_qty = floatval($total_qty) + floatval($for_loc_stock->quantity);
                                $stock1 = Stock::find($for_loc_stock->id);
                                $stock1->quantity = $new_qty;
                                $stock1->updated_by = $owner_id;
                                //$stock1->updated_at = $satisfy_when." 00:00:00";
                                $for_loc_result = $stock1->save();

                            } else {

                                $stock1 = new Stock();
                                $stock1->item_id = $processRequest->what_item_id;
                                $stock1->quantity = $total_qty;
                                $stock1->location_id = $processRequest->location_for;
                                $stock1->created_by = $owner_id;
                                $stock1->updated_by = $owner_id;
                                //$stock1->created_at = $satisfy_when." 00:00:00";
                                //$stock1->updated_at = $satisfy_when." 00:00:00";
                                $for_loc_result = $stock1->save();
                            }
                            if ( $for_loc_result ) {

                                foreach( $transaction_id as $key=>$trans ) {

                                    if ( isset($satisfy_qty[$key]) && $satisfy_qty[$key] > 0 ) {

                                        $stk_hist_add = new StockHistory();
                                        $stk_hist_add->transaction_id = $trans;
                                        $stk_hist_add->from_location = $processRequest->location_from;
                                        $stk_hist_add->to_location = $processRequest->location_for;
                                        $stk_hist_add->item_id = $processRequest->what_item_id;
                                        $stk_hist_add->quantity = $satisfy_qty[$key];
                                        $stk_hist_add->type = 'add';
                                        $stk_hist_add->created_by = $owner_id;
                                        $stk_hist_add->updated_by = $owner_id;
                                        //$stk_hist_add->created_at = $satisfy_when." 00:00:00";
                                        //$stk_hist_add->updated_at = $satisfy_when." 00:00:00";
                                        $check_status = $stk_hist_add->save();

                                        if ( !$check_status ) {

                                            DB::rollBack();
                                            $response['status'] = 'error';
                                            $response['msg'] = 'Stock record not updated';
                                            return json_encode($response);

                                        }
                                    }
                                }

                                DB::commit();
                                $response['status'] = 'success';
                                $response['msg'] = 'Request has been satisfied successfully';
                                return json_encode($response);


                            } else {

                                DB::rollBack();
                                $response['status'] = 'error';
                                $response['msg'] = 'Stock record not updated';
                                return json_encode($response);
                            }

                        } else {
                            DB::rollBack();
                            $response['status'] = 'error';
                            $response['msg'] = 'Stock record not updated';
                            return json_encode($response);
                        }

                    } else {
                        DB::rollBack();
                        $response['status'] = 'error';
                        $response['msg'] = 'Stock record not updated';
                        return json_encode($response);
                    }

                } else {
                    DB::rollBack();
                    $response['status'] = 'error';
                    $response['msg'] = 'Request not updated';
                    return json_encode($response);
                }

            } else {
                DB::rollBack();
                $response['status'] = 'error';
                $response['msg'] = 'Please insert satisfy quantity.';
                return json_encode($response);
            }

        }

	}

	public function getProcessStockDetail(){

		$loc_id = Request::get('loc_id');
		$satisfied_date = Request::get('satisfied_date');
		$user_id = Request::get('user_id');
		$loc_text = Request::get('loc_text');
		$owner_id = Auth::id();
        $view = Request::get('view');


		$item_requests = ItemRequest::groupBy('item_request.owner_by')
								->where('item_request.owner_by','=',$user_id)
								->where('item_request.owner_to','=',$owner_id)
								->where('item_request.satisfied','=',"No")->get();

		return view('requestItemProcess.processStockDetail',array('view'=>$view,'requests'=>$item_requests,'owner_id'=>$owner_id,'loc_text'=>$loc_text,'loc_id'=>$loc_id));

	}

}
