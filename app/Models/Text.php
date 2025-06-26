<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    use HasFactory;

    protected $table = "tbtext";
    protected $primaryKey = "t_id";

    protected $fillable = [
        "t_detail",
        "t_type",
        'display',
        'active'
    ];
}
