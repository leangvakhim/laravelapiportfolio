<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = "tbblog";
    protected $primaryKey = "b_id";

    protected $fillable = [
        "b_img",
        'b_title',
        "b_subtitle",
        "b_detail",
        "b_date",
        "b_order",
        'display',
        'active'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class, 'b_img', 'image_id');
    }
}
