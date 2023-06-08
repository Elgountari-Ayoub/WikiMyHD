<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function manual()
    {
        return $this->belongsTo(Manual::class);
    }
}
