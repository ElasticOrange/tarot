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
        return [
            'Australia',
            'Canada',
            'England',
            'New Zealand',
            'Romania',
            'USA',
        ];
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
