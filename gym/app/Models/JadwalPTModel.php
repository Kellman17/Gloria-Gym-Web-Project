<?php
namespace App\Models;

use CodeIgniter\Model;

class JadwalPTModel extends Model
{
    protected $table = 'jadwal_pt';
    protected $primaryKey = 'ID_Jadwal';
    protected $allowedFields = ['ID_PT', 'Nama_PT', 'Tanggal', 'Sesi1', 'Sesi2', 'Sesi3', 'Sesi4', 'Sesi5'];
    protected $useTimestamps = false;
}


?>