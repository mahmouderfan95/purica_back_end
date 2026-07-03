<?php

namespace App\Helper;

use Illuminate\Http\Request;

trait FileUpload
{
    public function save_file($file, $folder): string
    {
        $image_name = date('Ymd_His').'_'.rand().'.'.$file->getClientOriginalExtension();
        $file->storeAs('uploads/'.$folder,$image_name,'public');
        return $image_name;
    }

    public function remove_file($path,$name): void
    {
        if($name == 'default.png'){
            return;
        }

        $file_path = public_path('storage/uploads/').$path.'/'.$name;
        if(file_exists($file_path)) {
            unlink($file_path);
        }
    }
    public function uploadAttachments(String $type,$folder)
    {
        $uploadedFiles = [];

        if (request()->hasFile($type)) {
            $files = request()->file($type);

            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($folder, $fileName, 'public');

                    $uploadedFiles[] = [
                        'original_path' => $filePath,
                        'media_path' => asset('storage/' . $filePath),
                        'file_name' => $fileName,
                        'file_extension' => $file->getClientOriginalExtension(),
                        'is_main' => false,
                        'status' => 'active',
                    ];
                }
            }
        }

        return $uploadedFiles;

    }

}
