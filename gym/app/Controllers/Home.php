<?php

namespace App\Controllers;
use App\Models\MembershipModel;
use App\Models\PersonalTrainerModel;
use App\Models\InstrukturModel; // Tambahkan model untuk instruktur


class Home extends BaseController
{   
    protected $membershipModel;

    protected $personalTrainerModel;
    protected $instrukturModel;


    public function __construct()
    {
        // Inisialisasi model
        $this->personalTrainerModel = new PersonalTrainerModel();
        $this->instrukturModel = new InstrukturModel(); 
        $this->membershipModel = new MembershipModel();



    }
    public function index(): string
    {   // Ambil data dari model membership
        $membershipModel = new MembershipModel();
        $data['memberships'] = $membershipModel->findAll();  // Mengambil semua membership

        // Ambil data dari model personal trainer
        $trainerModel = new PersonalTrainerModel();
        $data['trainers'] = $trainerModel->findAll();  // Mengambil semua personal trainer

        // Ambil data dari model instruktur
        $instrukturModel = new InstrukturModel();
        $data['instrukturs'] = $instrukturModel->findAll();  // Mengambil semua personal trainer

        return view('Homegym', $data);
    }

    public function logout()
    {
         // Ambil data dari model personal trainer
         $trainerModel = new PersonalTrainerModel();
         $data['trainers'] = $trainerModel->findAll();  // Mengambil semua personal trainer
         return view('LoginPT', $data);
    }
}
