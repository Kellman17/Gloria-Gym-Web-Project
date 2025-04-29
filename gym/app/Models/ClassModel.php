<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'jadwal_class';
    protected $primaryKey = 'ID_Class';
    protected $allowedFields = ['Nama_Class', 'ID_Instruktur', 'Nama_Instruktur', 'Tanggal', 'Jam', 'Kuota'];
    protected $useTimestamps = false;



}
