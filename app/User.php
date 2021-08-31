<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Eloquent;

class User extends Authenticatable implements MustVerifyEmail
//class User extends Authenticatable 
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','google_id', 'facebook_id','role_id','phone_no','phone_verified_at','status','photo','dob','gender','email_verified_at','work_mail','referral_code','uuid','online','temp_mail','random_code','deactive_reason','password','wallet_point', 'last_activity','photo_temp','photo_status','account_register_reference','referral_register'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','temp_mail','random_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at'=> 'datetime',
        'last_activity' => 'datetime',
    ];

    public function AauthAcessToken(){
        return $this->hasMany('\App\OauthAccessToken');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole()
    {
        return $this->role->name;
    } 
    public function hasRoleSlug()
    {
        return $this->role->slug;
    } 
}
