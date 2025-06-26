<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;

    protected $table = "tbsocial";
    protected $primaryKey = "s_id";

    protected $fillable = [
        "s_img",
        's_title',
        "s_link",
        'display',
        'active'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class, 's_img', 'image_id');
    }
}
