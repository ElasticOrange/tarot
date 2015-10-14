<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Infocost extends Model
{
    protected $fillable = [
    	'active',
    	'default',
    	'country',
    	'telephone',
    	'infocost'
    ];

    public function site() {
    	return $this->belongsTo('App\Site');
    }

    public function countries() {
        $countries = \Config::get('countries.countries');

        if (empty($countries)) {
             $countries = [
                'England',
                'Romania'
            ];
        }

        return $countries;
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeDefault($query)
    {
        return $query->where('default', 1);
    }

}
