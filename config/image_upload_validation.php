<?php

return [

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