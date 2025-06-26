<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $table = "tbskill";
    protected $primaryKey = "sk_id";

    protected $fillable = [
        'sk_title',
        "sk_per",
        'display',
        'active'
    ];
}
