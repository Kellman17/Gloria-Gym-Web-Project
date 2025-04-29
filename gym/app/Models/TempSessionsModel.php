<?php 
namespace App\Models;

use CodeIgniter\Model;

class TempSessionsModel extends Model
{
    protected $table = 'temp_sessions';
    protected $primaryKey = 'ID_Sesi';
    protected $allowedFields = ['ID_PT', 'ID_Member', 'session_date', 'session_time', 'created_at'];
    protected $validationRules = [
        'ID_PT' => 'required|integer',
        'ID_Member' => 'required|integer',
        'session_date' => 'required|valid_date',
        'session_time' => 'required',
    ];

    public function getSessionsByMember($memberId)
    {
        return $this->where('ID_Member', $memberId)->findAll();
    }

    public function getSessionsByTrainer($trainerId)
    {
        return $this->where('ID_PT', $trainerId)->findAll();
    }
}

?>