<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function addresses(){
        return $this->hasMany(Address::class);
    }

    public function getAgeAttribute(){
        return Carbon::parse($this->birth_date)->age;
    }

    public function scopeSearch($query, $searchQuery)
    {
        return $query->where(function ($q) use ($searchQuery) {
            $q->where('first_name', 'like binary', "%{$searchQuery}%")
              ->orWhere('last_name', 'like binary', "%{$searchQuery}%")
              ->orWhere('email', 'like binary', "%{$searchQuery}%");
        });

        // case sensitive search for (like binary) other wise (like)
    }

    public function scopeAgeRange($query, $minAge = null, $maxAge = null){
        if($minAge !== null){
            $maxDate = Carbon::now()->subYears($minAge)->format('Y-m-d');
            $query->where('birth_date','<=',$maxDate);
        }

        if($maxAge !== null){
            $minDate = Carbon::now()->subYears($maxAge)->addDay()->format('Y-m-d');
            $query->where('birth_date','>=',$minDate);
        }

        return $query;
    }

    public function scopeByCity($query, $city){
        return $query->whereHas('addresses', function ($q) use ($city){
            $q->where('city', 'like',"%{$city}%");
        });
    }
}
