<?php

namespace App\Models;

use CodeIgniter\Model;

class OrangModel extends Model
{
    protected $table      = 'orang';
    protected $allowedFields = ['nama', 'alamat'];

    protected $useAutoIncrement = true;
    protected $useTimestamps = true;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function search($keyword){
        $builder = $this->table('orang');
        $builder->like('nama', $keyword)->orLike('alamat', $keyword);
        return $builder;
    }
}
