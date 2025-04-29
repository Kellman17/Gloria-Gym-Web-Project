<?php
namespace App\Models;

use CodeIgniter\Model;

class PersonalTrainingModel extends Model
{
    protected $table = 'personal_training';
    protected $primaryKey = 'ID_Sesi';
    protected $allowedFields = [
        'ID_PT', 
        'Nama_PT', 
        'ID_Member', 
        'Nama_Member', 
        'date', 
        'session_time', 
         'status',
        'rating', 
        'review',
        'Latihan',
        'Confirm',
        'Pesan'
    ];

    public function insertSession($data)
    {
        log_message('debug', '[DEBUG] Data untuk insertSession: ' . json_encode($data));
        if (!$this->insert($data)) {
            log_message('error', '[ERROR] insertSession - Gagal menyimpan: ' . json_encode($this->errors()));
            return false;
        }
        return true;
    }
    


    public function getTotalSessionsByMember($memberId)
{
    return $this->where('ID_Member', $memberId)->countAllResults();
}

    
}
?>
