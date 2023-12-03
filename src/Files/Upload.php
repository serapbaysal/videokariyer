<?php

namespace Files\Upload;

class Upload
{
    public function upload()
    {
        $path=$_FILES['file']['name'];

        $pathto= __DIR__ . "/uploads/".$path;
        move_uploaded_file( $_FILES['file']['tmp_name'],$pathto) or die( "Could not copy file!");
        move_uploaded_file($tmp_name, "$target_path/$name");

    }
}