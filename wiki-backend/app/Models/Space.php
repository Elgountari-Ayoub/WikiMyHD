<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Space extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function manuals()
    {
        return $this->hasMany(Manual::class, 'id_space');
    }

    
    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%');
        }
    }
}
