<?php

namespace App\Models;

use CodeIgniter\Model;

class MembershipRecordModel extends Model {
    protected $table = 'daftar_membership'; // Nama tabel di database
    protected $primaryKey = 'ID_Record'; // Primary key
    protected $allowedFields = [
        'ID_Member', 
        'Nama_Member', 
        'ID_Membership', 
        'Jenis_Membership', 
        'Harga', // Tambahkan kolom Harga
        'Pakai_PT', // Tambahkan kolom Pakai_PT
        'Tgl_Berlaku', 
        'Tgl_Berakhir', 
        'Bukti_Pembayaran', 
        'Status',
        'Alasan'
    ]; // Kolom yang bisa diisi
}
