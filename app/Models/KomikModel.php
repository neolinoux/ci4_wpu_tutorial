<?php

namespace App\Models;

use CodeIgniter\Model;

class KomikModel extends Model
{
    protected $table      = 'komik';
    protected $allowedFields = ['judul', 'slug', 'penulis', 'penerbit', 'sampul'];

    protected $useAutoIncrement = true;
    protected $useTimestamps = true;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getKomik($slug = null){
        if($slug == null){
            return $this->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }
}
