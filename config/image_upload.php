<?php

return [

    /*
    * Upload directory. Make sure this is can be accessed by public and writable.
    *
    * Default: public/uploads/images
    */
    'default_path' => 'image_upload',
    /*
     * Upload directory. Make sure this name is same as ur default_path.
     *
     * Default: public/uploads/images
     */
    'image_upload_path' => 'image_upload',
    /*
     * add watermark image path to create watermark image
     *
     */
    'watermark_image' => 'public/watermark_logo.png',
    /*
     * SET true to create resize images otherwise SET false
     *
     */
    'is_resize' => true,
    /*
     * SET true to create watermark image of original uploaded image otherwise SET false
     *
     */
    'is_watermark' => true,
    /*
     * SET true to create watermark image of resize uploaded image otherwise SET false
     *
     */
    'is_watermark_resize' => true,
    /*
     * Sizes, used to crop and create multiple size.
     *
     * array(width, height)
     */
    'dimensions' => [
        'size50' => [50, 50],
        'size200' => [200, 200]
    ],
    /*
     * For Add Validation Of Image
     *
    */
    'add_image_validation' => [
        'name' => 'required|min:3',
        'path' => 'required|image|mimes:jpeg,jpg,png'
    ],
    
    /*
     * For Update Validation Of Image
     * 
    */
    'update_image_validation' => [
        'name' => 'min:3',
        'path' => 'image|mimes:jpg,jpeg,png'
    ],

];