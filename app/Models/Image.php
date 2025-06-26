<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = "tbimage";
    protected $primaryKey = "image_id";

    protected $fillable = [
        "img"
    ];

    public function achievement(){
        return $this->hasMany(Achievement::class, "img", "image_id");
    }

    public function information()
    {
        return $this->hasMany(Information::class, "img", "image_id");
    }

    public function social()
    {
        return $this->hasMany(Social::class, "img", "image_id");
    }

    public function portfolio()
    {
        return $this->hasMany(Portfolio::class, "img", "image_id");
    }

    public function blog()
    {
        return $this->hasMany(Blog::class, "img", "image_id");
    }
}
