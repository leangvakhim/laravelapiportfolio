<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = "tbinformation";
    protected $primaryKey = "i_id";

    protected $fillable = [
        "i_img",
        'i_title',
        'i_type',
        "i_detail",
        'display',
        'active'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class, 'i_img', 'image_id');
    }
}
