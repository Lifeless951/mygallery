<?php

namespace MyGallery\Models;

use MyGallery\Core\Model;


class MainModel extends Model
{
    
    public function getPageData()
    {
        $data = $this->db->get('SELECT * FROM image_info');
        $result = [];
        for ($i = 0; $i < count($data); $i++) {
            $result[] = [
                'origPath' => $_SERVER['DOCUMENT_ROOT'] . '/uploaded_images/original/' . $data[$i]['file_name'],
                'thumbPath' => $_SERVER['DOCUMENT_ROOT'] . '/uploaded_images/thumbnail/' . $data[$i]['file_name'],
                'imageName' => $data[$i]['image_name'],
                'viewsCount' => $data[$i]['views_count']
            ];
        }
        return $result;
    }
}