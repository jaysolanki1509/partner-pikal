<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingAccountStatement;
use App\BookingRooms;
use App\City;
use App\Company;
use App\Country;
use App\Guest;
use App\Outlet;
use App\Owner;
use App\Room;
use App\RoomTypes;
use App\Salutation;
use App\State;
use App\Tables;
use App\Tax;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Event;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Integer;
use SebastianBergmann\Comparator\Book;
use Zend\Validator\Date;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){

        $data = [
            'page_title' => 'Booking',
        ];
        $outlet_id = Session::get('outlet_session');
        $room_types = RoomTypes::where('outlet_id',$outlet_id)->get();
        $room = array();
        $one_to_twenty = array();
        for($i=1;$i<=20;$i++){
            $one_to_twenty[$i]=$i;
        }
        $salutation = Salutation::all()->pluck("name","id");

        foreach ($room_types as $type){
            $room[$type->name] = $type->rooms;
        }
        $data['one_to_twenty'] = $one_to_twenty;
        $data["room_types"] = $room;
        $data["salutation"] = $salutation;

        return view('booking.index', $data);

    }

    public function bookingRoom() {

        $sess_outlet_id = Session::get('outlet_session');
        $check_in = Request::get("check_in");
        $room_id = Request::get("room_id");
        $days = Request::get("days");
        $salutation = Request::get("salutation");
        $first_name = Request::get("first_name");
        $last_name = Request::get("last_name");
        $contact_no = Request::get("contact_no");
        $adult = Request::get("adult");
        $child = Request::get("child");
        $no_of_rooms = Request::get("no_of_rooms");
        $reservation_type = Request::get("reservation_type");
        $logged_in_user = Auth::id();
        $deposit = Request::get("deposit");


        $getGuestNo = Guest::where('outlet_id',$sess_outlet_id)->pluck('guest_no');
        if(isset($getGuestNo) && sizeof($getGuestNo)>0){
            $gno = max($getGuestNo);
            $gno++;
        }else{
            $gno = 1;
        }
        $country = Country::all()->first();
        $state = State::all()->first();
        $city = City::all()->first();

        DB::beginTransaction();

            $guest = new Guest();
            $guest->outlet_id = $sess_outlet_id;
            $guest->guest_no = $gno;
            $guest->salutation_id = $salutation;
            $guest->first_name = $first_name;
            $guest->last_name = $last_name;
            $guest->gender = "male";
            $guest->mobile = $contact_no;
            $guest->created_by = $logged_in_user;
            $guest->updated_by = $logged_in_user;
            $guest->country_id = $country->id;
            $guest->state_id = $state->id;
            $guest->city_id = $city->id;
            $guest_result = $guest->save();

            $booking = new Booking();
            $booking->outlet_id = $sess_outlet_id;
            $booking->guest_id = $guest->id;
            $booking->check_in_date = date("Y-m-d",strtotime($check_in));
            $booking->check_out_date = date("Y-m-d",strtotime('+ '.$days.' days', strtotime($check_in)));
            $booking->duration = $days;
            $booking->no_of_extra_bed = 0;
            $booking->purpose = "";
            $booking->arrival_number = "";
            $booking->arrival_time = date("h:i:sa");
            $booking->departure_time = date("h:i:sa");
            $booking->deposit = $deposit;
            if($no_of_rooms > 1) {
                $booking->booking_type = "Group";
            }else{
                $booking->booking_type = "Single";
            }
            $booking->reservation_type = $reservation_type;
            $booking->no_of_rooms = $no_of_rooms;
            $booking->adult = $adult;
            $booking->child = $child;

            $room = Room::find($room_id);
            $room_type = RoomTypes::find($room->room_type_id);

            if($reservation_type == "check-in") {
                $booking->sub_total = ($room_type->base_price * $days) * $no_of_rooms;
                $booking->total = (($room_type->base_price * $days) * $no_of_rooms) - $deposit;
            }
            $booking->created_by = $logged_in_user;
            $booking->updated_by = $logged_in_user;
            $booking_result = $booking->save();

            if(isset($deposit) && $deposit > 0){

                $account = new BookingAccountStatement();
                $account->booking_id = $booking->id;
                $account->transaction_date = date('Y-m-d');
                $account->description = 'deposit';
                $account->charges = $deposit;
                $account->payments = $deposit;
                $account->created_by = $logged_in_user;
                $account->updated_by = $logged_in_user;
                $account->save();

            }

            for($i=0;$i<$no_of_rooms;$i++) {
                $book_room = new BookingRooms();
                $book_room->booking_id = $booking->id;
                $book_room->room_type_id = $room->room_type_id;
                $book_room->room_id = $room_id;
                $book_room->guest_id = $guest->id;
                $book_room->adult = $adult;
                $book_room->child = $child;
                $book_room->check_in_date = date("Y-m-d", strtotime($check_in));
                $book_room->check_out_date = date("Y-m-d", strtotime('+ ' . $days . ' days', strtotime($check_in)));
                $book_room->reservation_type = $reservation_type;
                $book_room->price = $room_type->base_price * $days;
                $book_room->created_by = $logged_in_user;
                $book_room->updated_by = $logged_in_user;
                $book_room_result = $book_room->save();

            }


        if($guest_result && $booking_result && $book_room_result){
            DB::commit();
            return "success";
        }else{
            DB::rollBack();
            return "error";
        }
    }

    public function loadCalender(){

        $start = Request::get("start_date");
        $end = Request::get("end_date");
        $count = 1;

        $outlet_id = Session::get('outlet_session');
        $room_types = RoomTypes::where('outlet_id',$outlet_id)->get();
        $room_typs = array();

        foreach ($room_types as $type){
            $room_typs[$type->name] = $type->rooms;
        }

        $bookings = Booking::with('booking_rooms','guests')
                            ->where('bookings.outlet_id',$outlet_id)
                            ->get();

        //generate date table
        $html = "";
        foreach($room_typs as $room_type=>$rooms) {     //Roomtype as id and $rooms as value
            //print_r($room_type."---".$rooms);exit;
            $html .= "<tr>";
            if ($count == 1) {
                $html .= "<td>Dates<br>Rooms</td>";
                $tempMonth = "";

                for($i=1; $i<=30;$i++){

                    $date = date("d",strtotime("+".$i." days", strtotime($start)));
                    $month = date("M",strtotime("+".$i." days", strtotime($start)));
                    $html .= "<td>" . $date . "</br>" . $month . "</td>";

                }

                $html .= "</tr>";
            }

            $first_day = date("Y-m-d",strtotime($start));
            $html .= "<tr>";
            $html .= "<td colspan='31'>$room_type</td>";     //Room type get as id
            $html .= "</tr>";
            foreach($rooms as $room) {                      //Room Loop
                $html .= "<tr>";
                $html .= "<td>$room->name</td>";
                $check_out_count = 0;
                $check_out_check = 0;
                for ($i = 1; $i <= 30; $i++) {              //Days Loop

                    $days = 0;
                    $room_id = 0;
                    $date = date("Y-m-d", strtotime("+" . $i . " days", strtotime($start)));
                    $guest_name = "NA";
                    $interval = 0;
                    $check_in_check = 0;
                    $booking_id = 0;
                    foreach ($bookings as $booking){        //Check Bookings
                        //print_r($booking);exit;
                        if($booking->check_in_date == $date){
                            $days = $booking->duration;
                            $booking_room = $booking->booking_rooms;
                            $room_id = $booking_room[0]->room_id;
                            $guest = $booking->guests;
                            $guest_name = $guest->first_name;
                            $booking_id = $booking->id;
                            $check_in_check = 1;
                            break;
                        }
                        if ($booking->check_in_date <= $first_day && $date < $booking->check_out_date && $check_out_count == 0){

                            $interval = date_diff(date_create($date),date_create($booking->check_out_date));
                            $booking_room = $booking->booking_rooms;
                            $room_id = $booking_room[0]->room_id;
                            $guest = $booking->guests;
                            $guest_name = $guest->first_name;
                            $booking_id = $booking->id;
                            $check_out_check = 1;
                            break;
                        }
                    }

                    if($room_id == $room->id && $check_in_check == 1) {

                        $html .= "<td colspan=$days onClick='checkDetail(this,$room->id)' bgcolor='#00FF00' data-room_id='$room->id' data-room='$room->name' data-bookingid='" . $booking_id . "'>$guest_name</td>";
                        if ($days != 0)
                            $i += $days - 1;
                    }elseif($check_out_count == 0 && $room_id == $room->id && $check_out_check == 1){
                        $check_out_count = 1;
                        $interval_days = $interval->days;
                        $html .= "<td colspan=$interval_days onClick='checkDetail(this,$room->id)' bgcolor='#00FF00' data-room_id='$room->id' data-room='$room->name' data-bookingid='" . $booking_id . "'>$guest_name</td>";
                        if ($interval_days != 0)
                            $i += $interval_days - 1;
                    }else{
                        $html .= "<td onClick='roomBookModel(this)' data-room_id='$room->id' data-room='$room->name' data-date='" . $date . "'></td>";
                    }

                }
                $count++;
                $html .= "</tr>";
            }
        }
        $data = array();
        $data['html'] = $html;
        $data['message'] = "success";
        return $data;

    }

    public function checkRoomDetails(){

        $booking_id = Request::get('booking_id');
        $room_id = Request::get('room_id');
        $outlet_id = Session::get('outlet_session');

        $bookings = Booking::join("booking_rooms","bookings.id","=","booking_id")
            ->join("guests","bookings.guest_id","=","guests.id")
            ->join("rooms","booking_rooms.room_id","=","rooms.id")
            ->join("salutations","salutations.id","=","guests.salutation_id")
            ->select("booking_rooms.*","rooms.name as room_name","bookings.*","booking_id","guests.*","salutations.name as salutation_name")
            ->where('bookings.outlet_id',$outlet_id)
            ->where('booking_rooms.room_id',$room_id)
            ->where('bookings.id',$booking_id)
            ->get();


        $data['booking'] = $bookings;
        $data['message'] = "success";

        return view('booking.bookview', $data);

    }

    public function edit($id){

        $outlet_id = Session::get('outlet_session');
        $bookings = Booking::join("booking_rooms","bookings.id","=","booking_id")
            ->join("guests","booking_rooms.guest_id","=","guests.id")
            ->join("rooms","booking_rooms.room_id","=","rooms.id")
            ->join("salutations","salutations.id","=","guests.salutation_id")
            ->select("booking_rooms.*",'booking_rooms.check_in_date as check_in','booking_rooms.check_out_date as check_out',
                "booking_rooms.id as booking_rooms_id","booking_rooms.room_id","booking_rooms.reservation_type as reservation_typ",
                "rooms.name as room_name","bookings.*","booking_id","guests.*","salutations.name as salutation_name",'booking_rooms.adult as adlt',
                'booking_rooms.child as chld')
            ->where('bookings.id',$id)
            ->get();

        $one_to_twenty = array();
        for($i=0;$i<=20;$i++){
            $one_to_twenty[$i]=$i;
        }

        $salutations = Salutation::all()->pluck("name","id");

        $room_types = RoomTypes::where('outlet_id',$outlet_id)->pluck("name","id");
        $rooms = Room::where('outlet_id',$outlet_id)->pluck("name","id");
        $outlet = Outlet::find($outlet_id);
        $all_tax = [];
        if(isset($outlet) && sizeof($outlet)>0){
            $all_taxes = json_decode($outlet->taxes);
            foreach ($all_taxes as $name=>$taxes){
                $all_tax["$name"] = $name;
            }
        }

        $country = Country::all()->pluck('name','id');
        $state = State::all()->pluck('name','id');
        $city = City::all()->pluck('name','id');


        return view('booking.form', array('country'=>$country,'state'=>$state,'city'=>$city,'all_tax'=>$all_tax,'rooms'=>$rooms,'room_types'=>$room_types,'booking'=>$bookings, 'salutations'=>$salutations,'one_to_twenty'=>$one_to_twenty));
    }

    public function calcRoomTaxTotal(){

        $sub_total = Request::get("sub_total");
        $tax_slab = Request::get("tax_slab");
        $booking_id = Request::get("booking_id");
        $booking = Booking::find($booking_id);

        $outlet_id = Session::get('outlet_session');
        $outlet = Outlet::find($outlet_id);
        $html = ""; $tax_total = 0;
        if(isset($outlet) && sizeof($outlet)>0){
            $all_taxes = json_decode($outlet->taxes);
            $selected_tax = array();
            if(isset($all_taxes) && sizeof($all_taxes)>0){
                foreach ($all_taxes as $slab=>$taxes){
                    if($slab == $tax_slab){
                        $selected_tax = $taxes;
                    }
                }
            }

            if(isset($selected_tax) && sizeof($selected_tax)>0){
                foreach ($selected_tax as $tax){
                    $html .= "<tr align='right' class='new_tax'><td colspan='7' style='font-weight: 700'>".$tax->taxname."</td><td>".number_format(($tax->taxparc*$sub_total)/100,2)."</td></tr>";
                    $tax_total += ($tax->taxparc*$sub_total)/100;
                }
            }
        }

        $deposit = 0;
        if(isset($booking) && sizeof($booking)>0){
            $deposit = $booking->deposit;
        }
        $data = array();
        $data['html'] = $html;
        $data['tax_total'] = $tax_total;
        $final_total = $sub_total + $tax_total - $deposit;

        $data['final_total'] = number_format($final_total,2);
        return $data;

    }

    public function update($id){

        $inputs = Request::all();
        $outlet_id = Session::get('outlet_session');
        //Guest Details
        $guest_id = $inputs['guest_id'];
        $salutation_id = $inputs['salutation_id'];
        $first_name = $inputs['first_name'];
        $last_name = $inputs['last_name'];
        $gender = $inputs['gender'];
        $id_proof = $inputs['id_proof'];
        $id_number = $inputs['id_number'];
        $dob = $inputs['dob'];
        $email = $inputs['email'];
        $country = $inputs['country'];
        $state = $inputs['state'];
        $city = $inputs['city'];

        //Booking Details
        $booking_id = $id;
        $purpose = $inputs['purpose'];
        $check_in_date = $inputs['check_in_date'];
        $check_out_date = $inputs['check_out_date'];
        $duration = $inputs['duration'];
        $book_adult = $inputs['adult'];
        $book_child = $inputs['child'];

        //Room Details
        $room_arr = $inputs['room'];
        $room_type_arr = $inputs['room_type'];
        $guest_name_arr = $inputs['guest_name'];
        $contact_arr = $inputs['contact'];
        $room_adult_arr = $inputs['room_adult'];
        $room_child_arr = $inputs['room_child'];
        $check_in_arr = $inputs['check_in'];
        $check_out_arr = $inputs['check_out'];
        $status_arr = $inputs['status'];
        $price_arr = $inputs['price'];
        $tax_name = $inputs['taxes'];
        $booking_rooms_id_arr = $inputs['booking_rooms_id'];
        $sub_total = array_sum($price_arr);
        $total_tax = Tax::calcTaxes($sub_total, $tax_name);

        $guest = Guest::find($guest_id);    //Guest Update
        if(isset($guest) && sizeof($guest)>0){
            $guest->salutation_id = $salutation_id;
            $guest->first_name = $first_name;
            $guest->last_name = $last_name;
            $guest->gender = $gender;
            $guest->id_proof = $id_proof;
            $guest->id_number = $id_number;
            $guest->dob = $dob;
            $guest->email = $email;
            $guest->country_id = $country;
            $guest->state_id = $state;
            $guest->city_id = $city;
            $guest->updated_by = Auth::id();
            $guest->save();
        }

        $booking = Booking::find($booking_id);      //Booking Update
        if(isset($booking) && sizeof($booking)>0){
            $booking->purpose = $purpose;
            $booking->check_in_date = $check_in_date;
            $booking->check_out_date = $check_out_date;
            $booking->check_out_date = $check_out_date;
            $booking->duration = $duration;
            $booking->adult = $book_adult;
            $booking->child = $book_child;
            $booking->taxes = $total_tax;
            $booking->total = $total_tax + $booking->total;
            $booking->updated_by = Auth::id();
            $booking->save();
        }

        if(sizeof($room_arr)>1) {          //If guest opt for more then 1 rooms(multiple guests)
            $guests_list = Guest::where('outlet_id',$outlet_id)
                                ->select('id','first_name','last_name')
                                ->get();

            for ($i = 0; $i < sizeof($room_arr); $i++) {
                $check = 0;
                foreach ($guests_list as $guests){
                    $guest_name = $guests->first_name.' '.$guests->last_name;
                    if(trim(strtolower($guest_name)) == trim(strtolower($guest_name_arr[$i]))){
                        $check = 1;
                        $guest_id = $guests->id;
                        break;
                    }
                }

                if($check == 0){            //If guest name and booking name is not same.

                    $getGuestNo = Guest::where('outlet_id',$outlet_id)->pluck('guest_no');
                    if(isset($getGuestNo) && sizeof($getGuestNo)>0){
                        $gno = max($getGuestNo);
                        $gno++;
                    }else{
                        $gno = 1;
                    }

                    $sal =  Salutation::all()->first();
                    $newguest = new Guest();
                    $name_arr = explode(' ',$guest_name_arr[$i]);
                    $newguest->salutation_id = $sal->id;
                    $newguest->outlet_id = $outlet_id;
                    $newguest->first_name = $name_arr[0];
                    $newguest->last_name = isset($name_arr[1])?$name_arr[1]:'';
                    $newguest->guest_no = $gno;
                    $newguest->gender = 'male';
                    $newguest->mobile = $contact_arr[$i];
                    $newguest->created_by = Auth::id();
                    $newguest->updated_by = Auth::id();
                    $newguest->save();

                    // Update Booked rooms guest details.
                    $booking_room = BookingRooms::find($booking_rooms_id_arr[$i]);
                    $booking_room->guest_id = $newguest->id;
                    $booking_room->room_type_id = $room_type_arr[$i];
                    $booking_room->room_id = $room_arr[$i];
                    $booking_room->adult = $room_adult_arr[$i];
                    $booking_room->child = $room_child_arr[$i];
                    $booking_room->check_in_date = $check_in_arr[$i];
                    $booking_room->check_out_date = $check_out_arr[$i];
                    $booking_room->reservation_type = $status_arr[$i];
                    $booking_room->price = $price_arr[$i];
                    $booking_room->updated_by = Auth::id();
                    $booking_room->save();
                }else{              //guest name is found then update details

                    $name_arr = explode(' ',$guest_name_arr[$i]);
                    $guest_update = Guest::find($guest_id);
                    $guest_update->outlet_id = $outlet_id;
                    $guest_update->first_name = $name_arr[0];
                    $guest_update->last_name = isset($name_arr[1])?$name_arr[1]:'';
                    $guest_update->gender = 'male';
                    $guest_update->mobile = $contact_arr[$i];
                    $guest_update->updated_by = Auth::id();
                    $guest_update->save();

                    // Update Booked rooms guest details.
                    $booking_room = BookingRooms::find($booking_rooms_id_arr[$i]);
                    $booking_room->guest_id = $guest_id;
                    $booking_room->room_type_id = $room_type_arr[$i];
                    $booking_room->room_id = $room_arr[$i];
                    $booking_room->adult = $room_adult_arr[$i];
                    $booking_room->child = $room_child_arr[$i];
                    $booking_room->check_in_date = $check_in_arr[$i];
                    $booking_room->check_out_date = $check_out_arr[$i];
                    $booking_room->reservation_type = $status_arr[$i];
                    $booking_room->price = $price_arr[$i];
                    $booking_room->updated_by = Auth::id();
                    $booking_room->save();
                }

            }
        }else{ // Single Guest

            $booking_room = BookingRooms::find($booking_rooms_id_arr[0]);
            $booking_room->guest_id = $guest_id;
            $booking_room->room_id = $room_arr[0];
            $booking_room->room_type_id = $room_type_arr[0];
            $booking_room->adult = $room_adult_arr[0];
            $booking_room->child = $room_child_arr[0];
            $booking_room->check_in_date = $check_in_arr[0];
            $booking_room->check_out_date = $check_out_arr[0];
            $booking_room->reservation_type = $status_arr[0];
            $booking_room->price = $price_arr[0];
            $booking_room->updated_by = Auth::id();
            $booking_room->save();
        }

        Session::flash('success', 'Booking updated successfully!');
        return redirect('/booking/'.$id.'/edit');
    }


    public function destroy($id){

        $booking_rooms_id = BookingRooms::where("booking_id",$id)->pluck('id');
        if(isset($booking_rooms_id) && sizeof($booking_rooms_id)>0){
            $user_id = Auth::id();
            foreach ($booking_rooms_id as $bk_id){
                $booking_room = BookingRooms::find($bk_id);
                $booking_room->deleted_by = $user_id;
                $booking_room->save();
                BookingRooms::destroy($bk_id);
            }
            $booking = Booking::find($id);

            if(isset($booking) && sizeof($booking)>0){
                $booking->deleted_by = $user_id;
                $booking->save();
                Booking::destroy($id);
                return "success";
            }else{
                return "error";
            }
        }else{
            return "error";
        }

    }

    public function checkInReport(Request $request) {

        if ($request->ajax()) {

            $from_date = Request::get('from_date');
            $to_date = Request::get('to_date');

            Session::set('from_session',$from_date);
            Session::set('to_session',$to_date);
            $outlet_id = Session::get('outlet_session');

            $bookings = Booking::with('booking_rooms','guests')
                ->where('bookings.outlet_id',$outlet_id)
                ->whereBetween('bookings.check_in_date',[$from_date,$to_date])
                ->get();

            $i = 0;
            $record = array();
            if(isset($bookings) && sizeof($bookings)>0) {
                foreach ($bookings as $book) {
                    $record[$i]['duration'] = Carbon::parse($book->check_in_date)->format('d M').' - '. Carbon::parse($book->check_out_date)->format('d M') .' ('.$book->duration.')';
                    $record[$i]['check_out_date'] = $book->check_out_date;
                    $booking_room = $book->booking_rooms;
                    $room = Room::find($booking_room[0]->room_id);
                    $room_type = RoomTypes::find($room->room_type_id);
                    $record[$i]['room_name'] = $room->name.' / '.$room_type->name;
                    $record[$i]['pax'] = $book->adult.'(A) - '.$book->child.'(C)';
                    $guest = $book->guests;
                    $record[$i]['guest_name'] = $guest->first_name." ".$guest->last_name;
                    $record[$i]['booking_id'] = $book->id;
                    $record[$i]['amount'] = $room_type->base_price*$book->duration;
                    $i++;
                }
            }

            return view('booking.checkInReportList', array('data' => $record));
        }

        return view('booking.checkInReport');

    }

    public function checkOutReport(Request $request) {

        if ($request->ajax()) {

            $from_date = Request::get('from_date');
            $to_date = Request::get('to_date');

            Session::set('from_session',$from_date);
            Session::set('to_session',$to_date);
            $outlet_id = Session::get('outlet_session');

            $bookings = Booking::with('booking_rooms','guests')
                ->where('bookings.outlet_id',$outlet_id)
                ->whereBetween('bookings.check_out_date',[$from_date,$to_date])
                ->get();

            $i = 0;
            $record = array();
            if(isset($bookings) && sizeof($bookings)>0) {
                foreach ($bookings as $book) {
                    $record[$i]['duration'] = Carbon::parse($book->check_in_date)->format('d M').' - '. Carbon::parse($book->check_out_date)->format('d M') .' ('.$book->duration.')';
                    $record[$i]['check_out_date'] = $book->check_out_date;
                    $booking_room = $book->booking_rooms;
                    $room = Room::find($booking_room[0]->room_id);
                    $room_type = RoomTypes::find($room->room_type_id);
                    $record[$i]['room_name'] = $room->name.' / '.$room_type->name;
                    $record[$i]['pax'] = $book->adult.'(A) - '.$book->child.'(C)';
                    $guest = $book->guests;
                    $record[$i]['guest_name'] = $guest->first_name." ".$guest->last_name;
                    $record[$i]['booking_id'] = $book->id;
                    $record[$i]['amount'] = $room_type->base_price*$book->duration;
                    $i++;
                }
            }

            return view('booking.checkOutReportList', array('data' => $record));
        }

        return view('booking.checkOutReport');

    }

    public function reservationReport(Request $request) {

        if ($request->ajax()) {

            $from_date = Request::get('from_date');
            $to_date = Request::get('to_date');
            $reservation_type = Request::get('reservation_type');

            Session::set('from_session',$from_date);
            Session::set('to_session',$to_date);
            $outlet_id = Session::get('outlet_session');

            $bookings = Booking::with('booking_rooms','guests')
                ->where('bookings.outlet_id',$outlet_id)
                ->where('bookings.reservation_type',$reservation_type)
                ->where('bookings.check_in_date','>=',$from_date)
                ->where('bookings.check_out_date','<=',$to_date)
                ->get();

            $i = 0;
            $record = array();

            if(isset($bookings) && sizeof($bookings)>0) {
                foreach ($bookings as $book) {
                    $record[$i]['duration'] = Carbon::parse($book->check_in_date)->format('d M').' - '. Carbon::parse($book->check_out_date)->format('d M') .' ('.$book->duration.')';
                    $record[$i]['check_out_date'] = $book->check_out_date;
                    $booking_room = $book->booking_rooms;
                    $room = Room::find($booking_room[0]->room_id);
                    $room_type = RoomTypes::find($room->room_type_id);
                    $record[$i]['room_name'] = $room->name;
                    $record[$i]['room_type'] = $room_type->name;
                    $record[$i]['pax'] = $book->adult.'(A) - '.$book->child.'(C)';
                    $guest = $book->guests;
                    $record[$i]['guest_name'] = $guest->first_name." ".$guest->last_name;
                    $record[$i]['contact'] = $guest->mobile;
                    $record[$i]['email'] = $guest->email;
                    $record[$i]['booking_id'] = $book->id;
                    $record[$i]['amount'] = $room_type->base_price*$book->duration;
                    $i++;
                }
            }

            return view('booking.reservationReportList', array('data' => $record));
        }

        return view('booking.reservationReport');

    }

    public function depositReport(Request $request){

        if ($request->ajax()) {

            $from_date = Request::get('from_date');
            $to_date = Request::get('to_date');

            Session::set('from_session',$from_date);
            Session::set('to_session',$to_date);
            $outlet_id = Session::get('outlet_session');

            $bookings = Booking::with('booking_rooms','guests')
                ->where('bookings.outlet_id',$outlet_id)
                ->where('bookings.deposit',">",0)
                ->where('bookings.check_in_date','>=',$from_date)
                ->where('bookings.check_out_date','<=',$to_date)
                ->get();

            $i = 0;
            $record = array();

            if(isset($bookings) && sizeof($bookings)>0) {
                foreach ($bookings as $book) {
                    $record[$i]['days'] = $book->duration;
                    $record[$i]['check_in_date'] = $book->check_in_date;
                    $booking_room = $book->booking_rooms;
                    $room = Room::find($booking_room[0]->room_id);
                    $record[$i]['room_name'] = isset($room)?$room->name:'NA';
                    $guest = $book->guests;
                    $record[$i]['contact'] = $guest->mobile;
                    $record[$i]['email'] = $guest->email;
                    $record[$i]['guest_name'] = $guest->first_name." ".$guest->last_name;
                    $record[$i]['deposit'] = '₹ '.number_format($book->deposit,2);
                    $record[$i]['booking_id'] = $book->id;
                    $i++;
                }
            }

            return view('booking.depositReportList', array('data' => $record));
        }

        return view('booking.depositReport');
    }

    public function policeReport(Request $request){

        if ($request->ajax()) {

            $from_date = Request::get('from_date');
            $to_date = Request::get('to_date');

            Session::set('from_session',$from_date);
            Session::set('to_session',$to_date);
            $outlet_id = Session::get('outlet_session');

            $bookings = Booking::with('booking_rooms','guests')
                ->where('bookings.outlet_id',$outlet_id)
                ->where('bookings.reservation_type',"check-in")
                ->where('bookings.check_in_date','>=',$from_date)
                ->where('bookings.check_out_date','<=',$to_date)
                ->get();

            $i = 0;
            $record = array();

            if(isset($bookings) && sizeof($bookings)>0) {
                foreach ($bookings as $book) {

                    //Guest Details
                    $guest = $book->guests;
                    $record[$i]['guest_name'] = ucfirst($guest->first_name)." ".ucfirst($guest->last_name);
                    $record[$i]['address'] = $guest->address!=''?$guest->address:'';
                    $mobile = $guest->mobile;
                    $email = isset($guest->email) && trim($guest->email) != ""?"/ \n".$guest->email:'';
                    $record[$i]['contact'] = $mobile.$email;
                    //Company Details
                    if(isset($guest->company_id) && sizeof($guest->company_id)>0) {
                        $company = Company::find($guest->company_id);
                        $record[$i]['company'] = $company->name."\n".$company->office_address;
                    }
                    else
                        $record[$i]['company'] = '';
                    //Duration Checkin CheckOut Days
                    $record[$i]['duration'] = $book->check_in_date."(In)\n".$book->check_in_date."(Out)\n".$book->duration.'(Days)';
                    //ID Proof
                    $proof_id = trim($guest->id_proof)!=""?ucfirst($guest->id_proof)."/ \n":'';
                    $record[$i]['id_proof'] = $proof_id.strtoupper($guest->id_number);

                    //Room Details
                    $booking_room = $book->booking_rooms;
                    $room = Room::find($booking_room[0]->room_id);
                    $pax = (integer)$book->adult + (integer)$book->child;
                    $room_type = RoomTypes::find($room->room_type_id);
                    $room_name = isset($room)?$room->name:'NA';
                    $record[$i]['room_details'] = $room_name."  / ".$pax."/ \n".$room_type->name;
                    $record[$i]['guest_details'] = trim($book->purpose)!=""?$book->purpose:"";

                    $i++;
                }
            }

            return view('booking.policeReportList', array('data' => $record));
        }

        return view('booking.policeReport');
    }

    public function noShow(Request $request)
    {

        if ($request->ajax()) {

            $from_date = Request::get('from_date');
            $to_date = Request::get('to_date');

            Session::set('from_session', $from_date);
            Session::set('to_session', $to_date);
            $outlet_id = Session::get('outlet_session');

            $bookings = Booking::with('booking_rooms', 'guests')
                ->where('bookings.outlet_id', $outlet_id)
                ->where('bookings.reservation_type', "!=","check-in")
                ->where('bookings.check_in_date', '>=', $from_date)
                ->where('bookings.check_out_date', '<=', $to_date)
                ->get();

            $i = 0;
            $record = array();

            if(isset($bookings) && sizeof($bookings)>0) {
                foreach ($bookings as $book) {

                    //Guest Details
                    $guest = $book->guests;
                    $created_by = $book->created_by;
                    $created_by_user = Owner::find($book->created_by);
                    $record[$i]['created_on'] = Carbon::parse($book->created_at)->format("Y-m-d");
                    $record[$i]['created_by'] = $created_by_user->user_name;
                    $record[$i]['guest_name'] = ucfirst($guest->first_name)." ".ucfirst($guest->last_name);
                    $record[$i]['booking_type'] = ucfirst($book->booking_type);
                    $record[$i]['status'] = ucfirst($book->reservation_type);

                    //Company Details
                    if(isset($guest->company_id) && sizeof($guest->company_id)>0) {
                        $company = Company::find($guest->company_id);
                        $record[$i]['company'] = $company->name."\n".$company->office_address;
                    }
                    else
                        $record[$i]['company'] = '';

                    //Room Details
                    $booking_room = $book->booking_rooms;
                    $room = Room::find($booking_room[0]->room_id);
                    $pax = (integer)$book->adult + (integer)$book->child;
                    $room_type = RoomTypes::find($room->room_type_id);
                    $room_name = isset($room)?$room->name:'NA';
                    $record[$i]['check_in'] = $book->check_in_date;
                    $record[$i]['check_out'] = $book->check_out_date;
                    $record[$i]['room_name'] = $room_name;
                    $record[$i]['pax'] = $pax;
                    $record[$i]['room_type'] = $room_type->name;

                    $i++;
                }
            }

            return view('booking.noShowList', array('data' => $record));
        }

        return view('booking.noShowReport');
    }

    public function occupancyAnalysis(Request $request)
    {

        if ($request->ajax()) {

            $from_date = Request::get('from_date');
            $to_date = Request::get('to_date');

            Session::set('from_session', $from_date);
            Session::set('to_session', $to_date);
            $outlet_id = Session::get('outlet_session');

            $room_types = RoomTypes::where('outlet_id',$outlet_id)->pluck('name','id');
            $i = 0;
            $record = [];
            foreach ($room_types as $id=>$type){

                $rooms = Room::where('room_type_id',$id)->pluck('id');

                $booked_rooms = Booking::join('booking_rooms','booking_rooms.booking_id','=',"bookings.id")
                                ->whereIn('booking_rooms.room_id',$rooms)
                                ->where('bookings.reservation_type', "=","check-in")
                                ->where('bookings.check_in_date', '>=', $from_date)
                                ->where('bookings.check_in_date', '<=', $to_date)->get();

                $record[$i]['room_type'] = $type;
                $total_rooms = sizeof($rooms);
                $record[$i]['total_rooms'] = $total_rooms;
                $revenue = 0;
                $sold_times = 0;
                foreach ($booked_rooms as $room){
                    $revenue += $room->total;
                    $sold_times ++;
                }
                $record[$i]['room_revenue'] = $revenue;
                $record[$i]['sold_rooms'] = $sold_times;
                $record[$i]['sold_rooms_perc'] = number_format(($sold_times*100)/$total_rooms,2);

                $single_rooms = Booking::join('booking_rooms','booking_rooms.booking_id','=',"bookings.id")
                    ->whereIn('booking_rooms.room_id',$rooms)
                    ->where('bookings.reservation_type', "=","check-in")
                    ->where('bookings.booking_type', "=","Single")
                    ->where('bookings.check_in_date', '>=', $from_date)
                    ->where('bookings.check_in_date', '<=', $to_date)->get();

                $record[$i]['single_sold'] = sizeof($single_rooms);

                $group_rooms = Booking::join('booking_rooms','booking_rooms.booking_id','=',"bookings.id")
                    ->whereIn('booking_rooms.room_id',$rooms)
                    ->where('bookings.reservation_type', "=","check-in")
                    ->where('bookings.booking_type', "=","Group")
                    ->where('bookings.check_in_date', '>=', $from_date)
                    ->where('bookings.check_in_date', '<=', $to_date)->get();

                $record[$i]['group_sold'] = sizeof($group_rooms);


                $i++;
            }
            print_r($record); exit;
            return view('booking.noShowList', array('data' => $record));
        }

        return view('booking.occupancyAnalysisReport');
    }

}
