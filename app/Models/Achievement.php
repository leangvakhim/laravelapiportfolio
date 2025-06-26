<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $table = "tbachievement";
    protected $primaryKey = "a_id";

    protected $fillable = [
        "a_img",
        'a_type',
        'display',
        'active'
    ];

    public function image(){
        return $this->belongsTo(Image::class, 'a_img', 'image_id');
    }
}
