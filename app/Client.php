<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
	protected $table = 'email_list_subscribers';
	use SoftDeletes;
    protected $properties = [];


	protected $primaryKey = 'subscriberid';


	public function getIdAttribute() {
		return $this->subscriberid;
	}

	public function setIdAttribute($value) {
		return $this->attributes['subscriberid'] = $value;
	}

	protected $dates = ['deleted_at'];

    public function data() {
        return $this->hasMany('\App\ClientData', 'subscriberid', 'subscriberid');
    }

    public function fields() {
        return $this->belongsToMany('App\ClientField', 'email_subscribers_data', 'subscriberid', 'fieldid')->withPivot('data');
    }

    public function site() {
        return $this->belongsTo('App\Site', 'listid', 'listid');
    }


    public function getProperties() {
    	$data = $this->data()->with('field')->get();

    	return $data;
    }



    public function fixName() {
        if (empty($this->fullName)) {
            $this->setFullNameAttribute($this->getFirstNameAttribute().' '.$this->getLastNameAttribute());
        }
    }

    public function fixFirstLastName() {
        $fullName = $this->getFullNameAttribute();
        preg_match('/(.*) (.*)/', trim($fullName), $nameParts);
        if (empty($nameParts[1])) {
            $nameParts[1] = $fullName;
        }

        if (empty($nameParts[2])) {
            $nameParts[2] = '';
        }

        if (empty($this->getFirstNameAttribute())) {
            $this->setFirstNameAttribute($nameParts[1]);
        }

        if (empty($this->getLastNameAttribute())) {
            $this->setLastNameAttribute($nameParts[2]);
        }
    }

    public function parseData() {
        foreach ($this->data as $property) {
            $this->properties[$property->field->name] = $property->data;
        }
        $this->fixName();
        $this->fixFirstLastName();
    }

    public function storeData() {
//TO DO
    }

    public function setProperty($propertyName, $value) {
        if (empty($this->properties)) {
            $this->parseData();
        }
        $this->properties[$propertyName] = $value;
    }

    public function getProperty($propertyName =  null) {
        if (empty($this->properties)) {
            $this->parseData();
        }

        if (!empty($this->properties[$propertyName])) {
            return $this->properties[$propertyName];
        }

        return "";
    }

    public function getFullNameAttribute() {

        return $this->getProperty('Name');
    }

    public function setFullNameAttribute($value) {
        $this->setProperty('Name', $value);
    }

    public function getFirstNameAttribute() {
        return $this->getProperty('First Name');
    }

    public function setFirstNameAttribute($value) {
        return $this->setProperty('First Name', $value);
    }

    public function getLastNameAttribute() {
        return $this->getProperty('Last Name');
    }

    public function setLastNameAttribute($value) {
        return $this->setProperty('Last Name', $value);
    }

    public function getGenderAttribute() {
        return $this->getProperty('Gender');
    }

    public function setGenderAttribute($value) {
        return $this->setProperty('Gender', $value);
    }

    public function getCountryAttribute() {
        return $this->getProperty('Country');
    }

    public function setCountryAttribute($value) {
        return $this->setProperty('Country', $value);
    }

    public function getInterestAttribute() {
        return $this->getProperty('Interest');
    }

    public function setInterestAttribute($value) {
        return $this->setProperty('Interest', $value);
    }
}
