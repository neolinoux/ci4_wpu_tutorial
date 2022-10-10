<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index(){
        $data = [
            'title' => 'Home | CI4'
        ];
        return view('pages/home', $data);
    }

    public function about(){
        $data = [
            'title' => 'About'
        ];
        return view('pages/about', $data);
    }

    public function contact(){
        $data = [
            'title' => 'Contact Us',
            'alamat' => [
                [
                    'tipe' => 'PTK',
                    'alamat' => 'Otista 63C',
                    'kota' => 'Jakarta Timur'
                ],
                [
                    'tipe' => 'PTK',
                    'alamat' => 'Tanggerang Selatan',
                    'kota' => 'Tanggerang'
                ]
            ],
        ];
        return view('pages/contact', $data);
    }
}
