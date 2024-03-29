<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_creator');
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function manual()
    {
        return $this->belongsTo(Manual::class);
    }
    public function versions()
    {
        return $this->hasMany(ArticleVersion::class);
    }
}
