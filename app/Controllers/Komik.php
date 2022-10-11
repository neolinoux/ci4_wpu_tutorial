<?php

namespace App\Controllers;

use App\Models\KomikModel;

class Komik extends BaseController
{
    protected $komikModel;
    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }

    public function index()
    {
        // $komik = $this->komikModel->findAll();
        $data = [
            'title' => 'Daftar Komik',
            'komik' => $this->komikModel->getKomik()
        ];

        //konek database manuaul
        // $db = \Config\Database::connect();
        // $komik = $db->query("select * from komik");
        // dd($komik);
        // foreach($komik->getResultArray() as $row){
        //     d($row);
        // }
        // $komikModel = new \App\Models\KomikModel();
        // dd($komik);

        return view('komik/index', $data);
    }

    public function detail($slug){
        // echo $slug;
        $data = [
            'title' => 'Detail komik',
            'komik' => $this->komikModel->getKomik($slug)
        ];

        if(empty($data['komik'])){
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan, missing in : '.$slug);
        }

        return view('komik/detail', $data);
    }

    public function create(){
        // session();
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => \Config\Services::validation()
        ];

        return view('komik/create', $data);
    }

    public function save(){
        if(!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} komik sudah ada'
                ]
                ],
                'sampul' => [
                    'rules' => 'is_image[sampul]|max_size[sampul,2048]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'max_size' => 'Ukuran gambar terlalu besar',
                        'is_image' => 'Bukan gambar',
                        'mime_in' => 'Format tidak sesuai'
                    ]
                ]
        ])){
            // $validation = \Config\Services::validation();
            // dd($validation);
            // return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
            return redirect()->to('/komik/create')->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');
        // dd($fileSampul);
        
        if($fileSampul->getError() == 4){
            $namaSampul = 'default.png';
        }else{
            $namaSampul = $fileSampul->getRandomName();
            $fileSampul->move('img', $namaSampul);
        }


        $slug = url_title($this->request->getVar('judul'),'-',true);

        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan!');

        return redirect()->to('/komik');
    }

    public function delete($id){
        $komik = $this->komikModel->find($id);
        
        if($komik['sampul'] != 'default.png' ){
            unlink('img/'.$komik['sampul']);
        }

        $this->komikModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus!');
        return redirect()->to('/komik');
    }

    public function edit($slug){
        $data = [
            'title' => 'Form Ubah Data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];

        return view('komik/edit', $data);
    }

    public function update($id){
        // dd($this->request->getVar());
        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        if($komikLama['judul'] == $this->request->getVar('judul')){
            $rule_judul = 'required';
        }else{
            $rule_judul = 'required|is_unique[komik.judul]';
        }
        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} komik sudah ada'
                ]
                ],
            'sampul' => [
                'rules' => 'is_image[sampul]|max_size[sampul,2048]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'is_image' => 'Bukan gambar',
                    'mime_in' => 'Format tidak sesuai'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->to('/komik/edit/'.$this->request->getVar('slug'))->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');

        if($fileSampul->getError() == 4){
            $namaSampul = $this->request->getVar('sampulLama');
        }else{
            $namaSampul = $fileSampul->getRandomName();
            
            $fileSampul->move('img', $namaSampul);

            unlink('img/' . $this->request->getVar('sampulLama'));
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data berhasil diubah!');

        return redirect()->to('/komik');
    }
}
