<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Http\Request;
use Mockery\CountValidator\Exception;

/**
 * Class ImageUpload
 * @package App
 */
class ImageUpload extends Model
{
    /**
     * @var string
     */
    protected $table = "imageuploads";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'path'
    ];

    /**
     * Image Name Change And Upload In Public Dir.
     * @param $image
     * @return string
     */
    public static function imageNameChange($image){
        $originalName = $image['path'];
        $imageExt = $originalName->getClientOriginalExtension();

        /* Size Check Of Image*/
        if($_FILES['path']['size'] > 50000){
            echo "File Size Is Too Large";
        }

        /* Image Extension Check*/
        if($imageExt != "jpg" && $imageExt != "jpeg" && $imageExt != "png"){
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
        $date = date("m-d-Y H:i:s");
        $newImageName = $date.'_'.$originalName->getClientOriginalName();
        $directory = "image_upload/";
        $imageStore = $originalName->move($directory,$newImageName);
        return $newImageName;
    }

    /**
     * Store Image In Database
     * @param $request
     * @return mixed
     */
    public static function imageUpload($request){
        if($request->file('path')){
            $newImageName = self::imageNameChange($request);
            $imageData = [
                'name' => $request->name,
                'path' => 'image_upload/'.$newImageName
            ];
            $data = ImageUpload::create($imageData);
            return $data;
        }
    }

    /**
     * Show All Image With Details
     * @return mixed
     */
    public static function getImage(){
        return ImageUpload::select()->paginate(3);
    }

    /**
     * Update Image Details
     * @param $id
     * @param $request
     * @return mixed
     */
    public static function updateImage($id, $request){

        /* For Old Image Path */
        try{
            $oldImagePath = ImageUpload::findOrfail($id);
        }
        catch(ModelNotFoundException $e){
            return response();
        }


        /* If Not Select Image Than*/
        if(isset($request->path)){
            $newImageName = self::imageNameChange($request);
            $path = 'image_upload/'.$newImageName;
            /* Remove Old Image */
            unlink($oldImagePath['path']);
        }
        else{
            $newImageName = $oldImagePath['path'];
            $path = $newImageName;
        }
        
        /* Update New Image*/
        $newData = [
            'name' => $request->name,
            'path' => $path
        ];
        $imageUpdate = ImageUpload::where('id', $id)
            ->update($newData);
        return $imageUpdate;
    }

    /**
     * Delete Image in Database As Well As Local
     * @param $id
     * @return mixed
     */
    public static function deleteImage($id){
        try{
            $imagePath = ImageUpload::findOrfail($id);
        }
        catch (ModelNotFoundException $e){
            return response();
        }
        unlink($imagePath['path']);
        $deleteImage = ImageUpload::where('id', $id)
            ->delete();
        return $deleteImage;
    }
}
