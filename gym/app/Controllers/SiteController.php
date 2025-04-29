<?php

namespace App\Controllers;
use App\Models\MembershipModel;
use App\Models\PersonalTrainerModel;
use App\Models\InstrukturModel; // Tambahkan model untuk instruktur
use App\Models\ClassModel;

class SiteController extends BaseController
{
    protected $membershipModel;
    protected $classModel;

    protected $personalTrainerModel;
    protected $instrukturModel;


    public function __construct()
    {
        // Inisialisasi model
        $this->personalTrainerModel = new PersonalTrainerModel();
        $this->instrukturModel = new InstrukturModel(); 
        $this->membershipModel = new MembershipModel();
        $this->classModel = new ClassModel();


    }
    public function about()
    {
        return view('homeabout'); // Mengarahkan ke halaman about.php
    }

    public function class()
    {
        return view('homeclass'); // Mengarahkan ke halaman class.php
    }
    public function class1()
    {
        return view('homeclass1'); // Mengarahkan ke halaman class.php
    }
    public function trainer()
    {
        // Ambil data dari model personal trainer
        $trainerModel = new PersonalTrainerModel();
        $data['trainers'] = $trainerModel->findAll();  // Mengambil semua personal trainer
        return view('hometrainer', $data); // Mengarahkan ke halaman trainer.php
    }
    public function trainer1()
    {
        // Ambil data dari model personal trainer
        $trainerModel = new PersonalTrainerModel();
        $data['trainers'] = $trainerModel->findAll();  // Mengambil semua personal trainer
        return view('hometrainer1', $data); // Mengarahkan ke halaman trainer.php
    }


    public function instructor()
    {
        // Ambil data dari model instruktur
        $instrukturModel = new InstrukturModel();
        $data['instrukturs'] = $instrukturModel->findAll();  // Mengambil semua personal trainer
        return view('homeinstructor', $data); // Mengarahkan ke halaman instructor.php
    }
    public function instructor1()
    {
        // Ambil data dari model instruktur
        $instrukturModel = new InstrukturModel();
        $data['instrukturs'] = $instrukturModel->findAll();  // Mengambil semua personal trainer
        return view('homeinstructor1', $data); // Mengarahkan ke halaman instructor.php
    }

    public function membership()
    { // Ambil data dari model membership
        $membershipModel = new MembershipModel();
        $classModel = new ClassModel();
        $data['memberships'] = $membershipModel->findAll();  // Mengambil semua membership
        $data['classes'] = $classModel->findAll(); 
        return view('homemembership', $data); // Mengarahkan ke halaman membership.php
    }

    public function contact()
    {
        return view('homecontact'); // Mengarahkan ke halaman contact.php
    }
}
