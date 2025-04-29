<?php

namespace App\Models;

use CodeIgniter\Model;

class TambahPTModel extends Model
{
    protected $table      = 'tambah_pt';
    protected $primaryKey = 'ID_Tambah_PT';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // Daftar field yang bisa di-assign
    protected $allowedFields = ['ID_Record', 'ID_PT', 'Harga_PT', 'Bukti_TambahPT', 'StatusPT','Reason'];

    public function getAddonPTWithDetails()
    {
        return $this->db->table($this->table)
            ->select('
                tambah_pt.ID_Tambah_PT,
                tambah_pt.ID_Record,
                tambah_pt.ID_PT,
                tambah_pt.Harga_PT,
                tambah_pt.Bukti_TambahPT,
                tambah_pt.StatusPT,
                tambah_pt.Reason, 
                daftar_membership.Tgl_Berlaku,
                daftar_membership.Tgl_Berakhir,
                daftar_membership.Nama_Member,
                personal_trainer.Nama_PT
            ')
            ->join('daftar_membership', 'tambah_pt.ID_Record = daftar_membership.ID_Record', 'left')
            ->join('personal_trainer', 'tambah_pt.ID_PT = personal_trainer.ID_PT', 'left')
            ->get()
            ->getResultArray();
    }

}
