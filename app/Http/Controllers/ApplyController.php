<?php
/**
 * Created by PhpStorm.
 * User: rubin
 * Date: 5/1/2015
 * Time: 12:27 PM
 */
namespace App\Http\Controllers;
use App\Outletimage;
use App\Outlet;
use Request;
use Validator;
use Redirect;

use Session;
class ApplyController extends Controller {
    public function multiple_upload() {
        // getting all of the post data
        $files = Request::file('images');
        if(!isset($files[0])){
            return Redirect::back()->with('error','Please select image.');
        }
        // Making counting of uploaded images
        $id=Request::get('restau_id');


        $file_count = count($files);
        // start count how many uploaded
        $uploadcount = 0;

        foreach($files as $file) {

            $rules = array('file' => 'required'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
            $validator = Validator::make(array('file'=> $file), $rules);

            if($validator->passes()){
                $destinationPath = 'uploads';
                $filename = $file->getClientOriginalName();
                $upload_success = $file->move($destinationPath, $filename);
                $upload_image=new Outletimage();
                $upload_image->outlet_id=$id;
                $upload_image->image_name=$filename;
                $upload_image->save();
                $uploadcount ++;
            }
        }
        if($uploadcount == $file_count){
            Session::flash('success', 'Upload successfully');
            return Redirect::to('outlet/'.$id.'#images');
        }
        else {
            return Redirect::to('outlet/'.$id)->withInput()->withErrors($validator);
        }
    }
    public function base64_to_jpeg( $base64_string, $output_file ) {
        $ifp = fopen( $output_file, "wb" );
        fwrite( $ifp, base64_decode( $base64_string) );
        fclose( $ifp );
        return( $output_file );
    }
    public function upload() {
        // getting all of the post data
        $files= Request::get('raw_data');
        //print_r($files);exit;
        $image=Request::file('image');
        if(!isset($image))
            return Redirect::back()->with('error','Please select image.');
        $id=Request::get('restau_id');
       //print_r($image->getClientOriginalName());exit;
        $removebase64=str_replace('data:image/png;base64,', '', $files);
        //print_r($removebase64);exit;
        $file=$this->base64_to_jpeg($removebase64,$image->getClientOriginalName());

        $destinationPath =  'uploads/profileimage';
        //  $filename = $file->getClientOriginalName();
        Request::file('image')->move($destinationPath, $file);
        //file_put_contents($destinationPath,$file);

        Outlet::where("id",$id)->update(array('Outlet_image'=>$image->getClientOriginalName()));

        // Making counting of uploaded images
        $id=Request::get('restau_id');

        return Redirect::to('outlet/'.$id);
       // $file_count = count($files);
        // start count how many uploaded
      //  $uploadcount = 0;


//print_r($file);exit;
                                                                                                                                                                                                                                                                                                                                                   $rules = array('file' => 'required'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
            $validator = Validator::make(array('file'=> $file), $rules);
       // print_R($validator->passes());exit;
            if($validator->passes()){

               // $upload=new Outlet();

               //  $uploadcount ++;
            }


            Session::flash('success', 'Upload successfully');



    }

    public function destroy()
    {

        Outletimage::where('id',Request::get('Outletimage_id'))->delete();

            Session::flash('flash_message', 'Successfully deleted the File!');
            return Redirect::to('outlet/'.Request::get('outlet_id').'#images');

    }
}