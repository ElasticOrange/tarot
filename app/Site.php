<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = [
		'name',
		'url',
		'email',
		'sender',
		'subject',
		'signature',
		'active'
	];

	public function infocosts() {
		return $this->hasMany('App\Infocost');
	}

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function users() {
        return $this->belongsToMany('\App\User')->withTimestamps();
    }
}
