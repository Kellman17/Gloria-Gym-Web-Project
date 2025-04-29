<?php

namespace App\Models;

use CodeIgniter\Model;

class InstrukturModel extends Model
{
    protected $table = 'instruktur';
    protected $primaryKey = 'ID_Instruktur';
    protected $allowedFields = ['Nama_Instruktur', 'Foto', 'Spesialisasi', 'Status'];
    protected $useTimestamps = false; // jika kamu tidak menggunakan timestamps
}
