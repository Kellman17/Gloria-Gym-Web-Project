<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingClassModel extends Model
{
    protected $table = 'booking_class'; // Nama tabel 
    protected $primaryKey = 'ID_Booking';
    protected $allowedFields = ['ID_Class', 'ID_Member', 'Tanggal_Booking','Status'];
}