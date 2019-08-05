<?php

namespace App\Http\Controllers;

use App\ImageUpload;
use Illuminate\Http\Request;

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
        return response()->json([
            'message' => 'Your Image Upload Successfully.',
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
            return response()->json([
                'message' => '!Opps Some Error Occurs While Update Data'
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
            return response()->json([
                'message' => 'Image Not Found.'
            ]);
        }
    }
}
