<?php

namespace App\Controllers;

use App\Models\OrangModel;

class Orang extends BaseController
{
    protected $orangModel;
    public function __construct()
    {
        $this->orangModel = new OrangModel();
    }

    public function index()
    {
        $keyword = $this->request->getVar('keyword');
        if($keyword){
            $orang = $this->orangModel->search($keyword);
        }else{
            $orang = $this->orangModel;
        }

        $currentPage = $this->request->getVar('page_orang') ? $this->request->getVar('page_orang') : 1;

        $data = [
            'title' => 'Daftar Orang',
            'orang' => $orang->paginate(6, 'orang'),
            'pager' => $this->orangModel->pager,
            'current_page' => $currentPage
        ];

        return view('orang/index', $data);
    }
}
