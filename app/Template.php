<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = [
		'name',
		'category',
		'type',
		'content',
        'subject',
        'sender_name',
		'active'
	];

    public function site() {
    	return $this->belongsTo('App\Site');
    }

    /**
     * Scope a query to only include active templates.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeOfCategory($query, $category) {
        return $query->where('category', strtolower($category));
    }

    public function scopeOfSite($query, $siteId) {
        return $query->where('site_id', $siteId);
    }
}
