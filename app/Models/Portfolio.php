<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $table = "tbportfolio";
    protected $primaryKey = "p_id";

    protected $fillable = [
        "p_img",
        'p_title',
        "p_category",
        "p_detail",
        "p_order",
        'display',
        'active'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class, 'p_img', 'image_id');
    }
}
