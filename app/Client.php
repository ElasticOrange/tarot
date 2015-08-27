<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ClientField;
use App\ClientData;
class Client extends Model
{
	protected $table = 'email_list_subscribers';
	use SoftDeletes;
    protected $properties = [];
    protected $dbProperties; // client data, array of values + fields loaded by getProperties()

	protected $primaryKey = 'subscriberid';


	public function getIdAttribute() {
		return $this->subscriberid;
	}

	public function setIdAttribute($value) {
		return $this->attributes['subscriberid'] = $value;
	}

	protected $dates = ['deleted_at'];

    protected $fillable = [
        'email',
        'firstName',
        'lastName',
        'gender',
        'birthDate',
        'partnerName',
        'interest',
        'country',
        'ignore',
        'problem',
        'comment'
    ];

    public function data() {
        return $this->hasMany('\App\ClientData', 'subscriberid', 'subscriberid');
    }

    public function fields() {
        return $this->belongsToMany('App\ClientField', 'email_subscribers_data', 'subscriberid', 'fieldid')->withPivot('data');
    }

    public function site() {
        return $this->belongsTo('App\Site', 'listid', 'listid');
    }

    public function save(array $options = array()) {
        $result = parent::save($options);
        if ($result) {
            $result = $this->saveProperties();
        }
        return $result;
    }

    public function update(array $attributes = array()) {
        if (!is_array($attributes)) {
            \Log::error('Client->update(): invalid $attributes parameter', ['attributes' => $attributes]);
            return false;
        }
        foreach($attributes as $name => $value) {
            if (array_search($name, $this->fillable) !== false){
                $this->$name = $value;
            }
        }
        return $this->save();
    }

    public function saveProperty($propertyName) {
        if (!array_key_exists($propertyName ,$this->properties)) {
            \Log::error('Client->saveProperty: trying to save not set property', ['propertyName' => $propertyName]);
            return false;
        }
        $properties = $this->getProperties();
        $saved = false;

        foreach ($properties as $value) {
            if ($value->field->name == $propertyName) {
                $value->data = $this->properties[$propertyName];
                $value->save();
                return true;
            }
        }

        // If we got here the value does not exist in the db so it must be created
        return $this->createPropertyValue($propertyName);
    }

    public function createPropertyValue($propertyName) {
        $field = \App\ClientField::getByName($propertyName);

        if (!$field) {
            \Log::error('Client->createPropertyValue: field does not exist', ['fieldName' => $propertyName]);
            return false;
        }

        $value = new \App\ClientData;
        $value->fieldid = $field->id;
        $value->subscriberid = $this->id;
        $value->data = $this->getProperty($propertyName);

        return $value->save();
    }

    public function saveProperties() {
        foreach ($this->properties as $propertyName => $value) {
            $this->saveProperty($propertyName);
        }
    }

    public function getProperties() {
        if (empty($this->dbProperties)) {
    	   $this->dbProperties = $this->data()->with('field')->get();
        }
    	return $this->dbProperties;
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
        $properties = $this->getProperties();

        foreach ($properties as $property) {
            if (empty($this->properties[$property->field->name])) {
                $this->properties[$property->field->name] = $property->data;
            }
        }
        $this->fixName();
        $this->fixFirstLastName();
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


    public function getEmailAttribute() {
        return $this->attributes['emailaddress'];
    }

    public function setEmailAttribute($value) {
        $this->attributes['emailaddress'] = $value;
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

    public function getBirthDateAttribute() {
        $date = $this->getProperty('Birth Date');

        try {
            $date = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        }
        catch(\Exception $e) {
        }
        return $date;
    }

    public function setBirthDateAttribute($isoDate) {
        try {
            $date = \Carbon\Carbon::createFromFormat('Y/m/d', $isoDate)->format('d/m/Y');
        }
        catch (\Exception $e) {
            $date = $isoDate;
        }

        return $this->setProperty('Birth Date', $date);
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
