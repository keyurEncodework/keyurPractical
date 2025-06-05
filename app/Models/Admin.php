<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasApiTokens, Notifiable;

     public function getAvatarAttribute($avatar) {
        if ($avatar == '' || $avatar == 'default.png') {
            return asset('assets/images/dashboard/default.png');
        }else {
            return asset('s/images/profile/'. $avatar);
        }
    }
}
