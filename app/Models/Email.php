<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $table = "tbemail";
    protected $primaryKey = "e_id";

    protected $fillable = [
        "e_name",
        'e_email',
        "e_detail",
        'active'
    ];
}
