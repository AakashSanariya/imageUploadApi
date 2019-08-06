<?php

namespace App\Http\Controllers;

use App\Events\EmailSendEvent;
use App\Events\Event;
use App\ImageUpload;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class ImageUploadController
 * @package App\Http\Controllers
 */
class ImageUploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Insert Image In Database
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $validation = $this->validate($request, [
            'name' => 'required|min:3',
            'path' => 'required|image|mimes:jpeg,jpg,png'
        ]);
        $imageUpload = ImageUpload::imageUpload($request);
        $allImage = ImageUpload::getImage();
        $receiverEmail = [
            "fromEmail" => "crazydev82@gmail.com",
            "to" => "akash.sanariya@brainvire.com",
            "data" => "Your Image Add Successfully",
            "subject" => "Added Image Successfully"
        ];
        /* For Sending Mail*/
        event(new EmailSendEvent($receiverEmail));
        return response()->json([
            'message' => 'Your Image Upload Successfully.',
            'mail' => 'Check Your Mail Also',
            'Image' => $allImage
        ], 200);
    }

    /**
     * Show All Image
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $imageShow = ImageUpload::getImage();
        return response()->json([
            'message' => 'Receive Image Successfully',
            'Data' => $imageShow
        ], 200);
    }

    /**
     * Update Image
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request){
        $validation = $this->validate($request, [
            'name' => 'min:3',
            'path' => 'image|mimes:jpg,jpeg,png'
        ]);
        $imageUpdate = ImageUpload::updateImage($id, $request);
        if($imageUpdate == "1"){
            return response()->json([
                'message' => 'Update details Successfully'
            ], 200);
        }
        else{
            Log::error(['There was not data on this Id', $id]);
            return response()->json([
                'message' => 'Data Could Not Be Found'
            ]);
        }
    }

    /**
     * Delete Image in Database As Well As Local
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id){
        $imageDelete = ImageUpload::deleteImage($id);
        if($imageDelete == "1"){
            return response()->json([
                'message' => "Image Delete Successfully"
            ], 200);
        }
        else{
            Log::critical(['This Id would not found also It will be Deleted', $id]);
            return response()->json([
                'message' => 'Image Not Found.'
            ]);
        }
    }

    public function userLogin(Request $request){
        $userAuth = User::getLogin($request);
        if($userAuth != "NULL"){
            return response()->json([
                'message' => 'Login Successfully',
                'token' => $userAuth
            ], 200);
        }
        else{
            return response()->json([
                'message' => '!Opps Some Error Occurs In Login'
            ]);
        }
    }
}
