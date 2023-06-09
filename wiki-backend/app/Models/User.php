<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Space;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'photo',
    //     'user_name',
    //     'post',
    //     'status',
    //     'role',
    // ];

    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function has()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    
    public function spaces()
    {
        return $this->belongsToMany(Space::class)->withPivot('is_creator');
    }
    public function manuals()
    {
        return $this->belongsToMany(Manual::class)->withPivot('is_creator');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withPivot('is_creator');
    }
    
}
