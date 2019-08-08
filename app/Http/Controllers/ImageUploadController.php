<?php

namespace App\Http\Controllers;

use App\Events\EmailSendEvent;
use App\Http\Helpers\UploadImageTrait;
use App\ImageUpload;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mockery\CountValidator\Exception;
use Prophecy\Exception\Doubler\MethodNotFoundException;

/**
 * Class ImageUploadController
 * @package App\Http\Controllers
 */
class ImageUploadController extends Controller
{
    use UploadImageTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('role_permission:LIST_IMAGE', ['only' => 'index']);
        $this->middleware('role_permission:ADD_IMAGE', ['only' => 'store']);
        $this->middleware('role_permission:UPDATE_IMAGE', ['only' => 'update']);
        $this->middleware('role_permission:DELETE_IMAGE', ['only' => 'delete']);
    }

    /**
     * Insert Image In Database
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $validations = config('image_upload.add_image_validation');
        $validation = Validator::make($request->all(), $validations);
        if($validation->fails()){
            return $this->validationError($validation);
        }
        try{
            $allImage = ImageUpload::getImage();
            $receiverEmail = [
                "fromEmail" => "crazydev82@gmail.com",
                "to" => "akash.sanariya@brainvire.com",
                "data" => "Your Image Add Successfully",
                "subject" => "Added Image Successfully"
            ];

            /* For Image Upload Using BaseStructure */
            $userId = Auth::user()->id;
            $image = $request->file('path');
            if($image){
                $imagePath = $this->uploadProfile($image,$userId);
            }
            $imageUpload = ImageUpload::imageUpload($request,$imagePath);
            /* For Sending Mail*/
            event(new EmailSendEvent($receiverEmail));
            return $this->success(null, 'IMAGE_INSERT_SUCCESS', 200);
        }
        catch (MethodNotFoundException $e){
                return $this->error($e->getMessage());
        }

    }

    /**
     * Show All Image
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $imageShow = ImageUpload::getImage();
        try{
            return $this->success(['ImageData' => $imageShow], 'IMAGE_LIST_SUCCESS', 200);
        }
        catch (Exception $e){
            return $this->error('ERROR_FETCH_RECORDS_OF_IMAGE');
        }
    }

    /**
     * Update Image
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request){
        $validations = config('image_upload.update_image_validation');
        $validation = Validator::make($request->all(), $validations);
        if($validation->fails()){
            return $this->validationError($validation);
        }
        try{
            $image = $request->file('path');
            if($image != "NULL"){
                $imageName = $this->uploadProfile($image, $id);
            }
            $imageUpdate = ImageUpload::updateImage($id, $request, $imageName);
            return $this->success(null, 'UPDATE_IMAGE_SUCCESS', 200);
        }
        catch (Exception $e){
            Log::error(['There was not data on this Id', $id]);
            return $this->error('IMAGE_NOT_EXISTS', 404);
        }
    }

    /**
     * Delete Image in Database As Well As Local
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id){
        try{
            $imageDelete = ImageUpload::deleteImage($id);
            if($imageDelete == false){
                return $this->error('IMAGE_NOT_FOUND', 404);
            }
            return $this->success(null, 'IMAGE_DELETE_SUCCESS', 200);
        }
        catch (Exception $e){
            Log::critical(['This Id would not found also It will be Deleted', $id]);
            return $this->error('IMAGE_NOT_DELETE', 400);
        }
    }

    public function userLogin(Request $request){
        $userAuth = User::getLogin($request);
        if($userAuth != "NULL"){
            return $this->success(['token' => $userAuth], 'Admin User is Logging', 200);
        }
        else{
            return $this->error('TOKEN_NOT_GENERATED', 401);
        }
    }

    public function uploadProfile($image, $id){
        if ($image) {
            $imageData = [
                'id' => $id,
                'image' => $image,
                'folder_name' => 'image_upload'
            ];
            $imageName = $this->uploadImage($imageData);
            if ($imageName) {
                return $imageName;
            } else {
                return 'Error in image upload!';
            }
        }
    }
}
