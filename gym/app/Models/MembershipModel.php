<?php 
namespace App\Models;

use CodeIgniter\Model;

class MembershipModel extends Model
{
    protected $table = 'membership';  // Nama tabel
    protected $primaryKey = 'ID_Membership';
    protected $allowedFields = ['Jenis_Membership', 'Durasi', 'Harga'];
    public function getAllMemberships()
    {
        return $this->findAll();
    }
}
?>