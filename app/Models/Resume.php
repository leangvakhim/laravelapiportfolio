<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $table = "tbresume";
    protected $primaryKey = "r_id";

    protected $fillable = [
        'r_title',
        "r_duration",
        "r_detail",
        "r_type",
        'display',
        'active'
    ];
}
