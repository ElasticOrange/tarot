<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'type',
        'active'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public static function getUserTypes() {
        return [
            1 => 'Administrator',
            2 => 'Editor',
            3 => 'Responder',
            4.=> 'Guest'

        ];
    }

    public function sites() {
        return $this->belongsToMany('\App\Site')->withTimestamps();
    }

    public function currentSiteId() {
        $currentSiteId = session('currentSiteId');

        if (!$currentSiteId) {
            $firstSite = $this->sites()->first();

            if (!$firstSite) {
                return false;
            }

            if ($firstSite->id) {
                $currentSiteId = $firstSite->id;
            }
        }
        return $currentSiteId;
    }


    public function setCurrentSiteId($siteId) {
        if ($this->sites()->lists('id')->contains($siteId)) {
            session(['currentSiteId' => $siteId]);
            return true;
        }

        return false;
    }
}
