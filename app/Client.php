<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ClientField;
use App\ClientData;

function getDomainFromEmailAddress($emailAddress) {
    $domain = preg_match('/.+(\@.+\..+)/', $emailAddress, $matches);

    if (empty($matches)) {
        return false;
    }

    return $matches[1];
}

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

    public $timestamps = false;

    protected $dates = ['opened_at', 'deleted_at', 'confirmdate'];

    protected $fillable = [
        'email',
        'name',
        'firstName',
        'lastName',
        'gender',
        'birthDate',
        'partnerName',
        'interest',
        'country',
        'ignore',
        'problem',
        'comment',
        'listid'
    ];

    public function data() {
        return $this->hasMany('\App\ClientData', 'subscriberid', 'subscriberid');
    }

    public function emails() {
        return $this->hasMany('\App\Email', 'from_email', 'emailaddress');
    }

    public function sentEmails() {
        return $this->hasMany('\App\Email', 'to_email', 'emailaddress');
    }

    public function fields() {
        return $this->belongsToMany('App\ClientField', 'email_subscribers_data', 'subscriberid', 'fieldid')->withPivot('data');
    }

    public function site() {
        return $this->belongsTo('App\Site', 'listid', 'listid');
    }

    public function hasSite() {
        if ($this->listid > 0) {
            return true;
        }

        return false;
    }

    public function getSite() {
        return $this->site()->first();
    }

    static public function getBySiteAndEmailAddress($siteId, $emailAddress) {
        $instance = new static;

        return $instance->emailAddress($emailAddress)->site($siteId)->first();
    }

    public function scopeEmailAddress($query, $emailAddress) {
        return $query->where('emailaddress', $emailAddress);
    }

    public function scopeSite($query, $siteId) {
        return $query->where('listid', $siteId);
    }

    public function scopeConfirmed($query) {
        return $query->where('confirmed', 1);
    }

    public function scopeWithQuestionUnresponded($query) {
        return $query->where('questionAnswered', 0);
    }

    public function scopeForSite($query, $siteId) {
        return $query->where('listid', $siteId);
    }

    public function scopeIsActive($query) {
        return $query->where('confirmed', 1)->where('ignore', 0)->where('unsubscribed', 0);
    }

    public function setQuestionAnswered() {
        if (! $this->questionAnswered) {
            $this->questionAnswered = 1;
            $this->save();
        }

        return true;
    }

    public function setQuestionUnanswered() {
        if ($this->questionAnswered) {
            $this->questionAnswered = 0;
            $this->save();
        }

        return true;
    }

    public function setDefaultAttributes() {
        $this->domainname = getDomainFromEmailAddress($this->emailaddress);
        if (!$this->format) {
            $this->format = 'h';
        }

        if ($this->confirmed === null) {
            $this->confirmed = 1;
        }

        if (!$this->confirmdate) {
            $this->confirmdate = time();
        }

        if (!$this->subscribedate) {
            $this->subscribedate = time();
        }

        if (!$this->formid) {
            $this->formid = $this->getSite()->getForm()->id;
        }

        return true;
    }

    public function save(array $options = array()) {
        if (!$this->hasSite()) {
            dd('Client does not have site set!');
            return false;
        }

        if (!$this->setDefaultAttributes()) {
            return false;
        }

        $result = parent::save($options);
        if ($result) {
            $result = $this->saveProperties();
        }
        return $result;
    }

    static public function byEmailAndSite($email, $siteId) {
        $instance = new static;
        return $instance->where('emailaddress', $email)->where('listid', $siteId)->first();
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

    public function getAllFields() {
        return \App\ClientField::all();
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

    public function setFormFromSite() {
        $site_form = $this->site()->first()->getForm();
        $this->formid = $site_form->id;
        return true;
    }

    public function getFields() {
        if (!$this->fieldid) {
            $this->setFormFromSite();
        }

        return $this->fields()->get();
    }

    public function createProperties() {
        $fields = $this->getFields();

        if ($fields->isEmpty()) {
            return false;
        }

        foreach ($fields as $key => $field) {
            $this->properties[$field->name] = $field->defaultvalue;
        }

        return true;
    }

    public function parseData() {
        $properties = $this->getProperties();

        if ($properties->isEmpty()) {
            return $this->createProperties();
        }

        foreach ($properties as $property) {
            if (empty($this->properties[$property->field->name])) {
                $this->properties[$property->field->name] = $property->data;
            }
        }

        return true;
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
        if (array_key_exists('emailaddress', $this->attributes)) {
            return $this->attributes['emailaddress'];
        }

        return '';
    }

    public function setEmailAttribute($value) {
        $this->attributes['emailaddress'] = $value;
    }

    public function getNameAttribute() {
        $name = $this->getProperty('Name');

        if (empty($name)) {
            $name = $this->getProperty('First name');
        }

        if (empty($name)) {
            $name = $this->getProperty('First Name');
        }

        if (empty($name)) {
            $name = $this->getProperty('Last name');
        }

        if (empty($name)) {
            $name = $this->getProperty('Last Name');
        }

        return $name;
    }

    public function setNameAttribute($value) {
        $oldName = $this->getProperty('Name');

        if (array_key_exists('Name', $this->properties)) {
            $this->setProperty('Name', $value);
            return;
        }

        if (array_key_exists('First name', $this->properties)) {
            $this->setProperty('First name', $value);
            return;
        }


        if (array_key_exists('Last name', $this->properties)) {
            $this->setProperty('Last name', $value);
            return;
        }

        $this->setProperty('Name', $value);
    }

    public function getFullNameAttribute() {
        return $this->getNameAttribute();
    }

    public function setFullNameAttribute($value) {
        $this->setNameAttribute($value);
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

    public function getQuestionAttribute() {
        return $this->getProperty('Please tell me your question and how I can reach you to send you your answer');
    }

    public function setQuestionAttribute($value) {
        return $this->setProperty('Please tell me your question and how I can reach you to send you your answer', $value);
    }


    public function getBirthDateAttribute() {
        $date = $this->getProperty('Birth Date');

        try {
            $date = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        }
        catch(\Exception $e) {
            \Log::error('Exception in Client::getBirthDateAttribute when transforming date from '.$date);
        }
        return $date;
    }

    public function setBirthDateAttribute($isoDate) {
        try {
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $isoDate)->format('d/m/Y');
        }
        catch (\Exception $e) {
            \Log::error('Exception in Client::setBirthDateAttribute when transforming date from '.$isoDate);
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
/*
    public function getInterestAttribute() {
        return $this->getProperty('Interest');
    }

    public function setInterestAttribute($value) {
        return $this->setProperty('Interest', $value);
    }
*/
    public function isSubscribed() {
        if ($this->unsubscribed == 0) {
            return true;
        };

        return false;
    }

    public function getClone($site_id) {
        $client = new Client(['listid' => $site_id]);

        // copy attributes
        foreach ($this->attributes as $attribute => $value) {
            if (in_array( $attribute, ['listid', 'subscriberid', 'created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
            $client->setAttribute($attribute, $value);
        }

        // copy properties
        $this->parseData();
        foreach ($this->properties as $property => $value) {
            $client->setProperty($property, $value);
        }

        return $client;
    }

    public function setAsSubscribed() {
        $this->unsubscribed = 0;
    }

    public function setAsUnsubscribed() {
        $this->unsubscribed = time();
    }

    static public function getClientsWithUnrespondedQuestionsForSite($site) {
        if (!$site) {
            return false;
        }

        $clients = $site->clients()->isActive()->withQuestionUnresponded()->with(['data','fields'])->orderBy('confirmdate')->get();

        $clients = Client::filterClientsWithValidAge($clients);

        return $clients;
    }

    static public function getClientWithUnrespondedQuestionsForSite($site) {
        if (!$site) {
            return false;
        }
        $instance = new static;
        $clients = $instance->isActive()->forSite($site->id)->withQuestionUnresponded()->with(['data','fields'])->orderBy('confirmdate', 'desc')->first();
        return $clients;
    }

    public function getFirstUnrespondedEmail() {
        return $this->emails()->unresponded()->first();
    }

    public function getUnrespondedEmails($site) {
        return $this->emails()->unresponded()->toSite($site)->get();
    }

    public function getLastEmail($site) {
        return $this->emails()->toSite($site)->orderBy('sent_at', 'desc')->first();
    }

    public function markEmailsAsResponded($site) {
        $unrespondedEmails = $this->getUnrespondedEmails($site);
        if (!$unrespondedEmails or $unrespondedEmails->isEmpty()) {
            return false;
        }

        foreach ($unrespondedEmails as $email) {
            $email->responded = true;
            $email->save();
        }

        return true;
    }

    public function markLastEmailAsUnresponded($site) {
        $lastEmail = $this->getLastEmail($site);
        if (!$lastEmail) {
            return false;
        }

        $lastEmail->responded = false;
        $lastEmail->save();

        return true;
    }

    public static function filterByString($filterValue) {
        $instance = new static;
    }

    public function scopeCountBySearchTerm($query, $searchTerm) {
        $clientData = new \App\ClientData();
        $clientDataTable = $clientData->getTable();
        $clientTable = $this->getTable();

        return $query   ->distinct()
                        ->leftJoin( $clientDataTable,
                                    $clientTable.'.'.$this->getKeyName(),
                                    '=',
                                    $clientDataTable.'.'.$this->getKeyName())
                        ->whereRaw('(emailaddress like ? or comment like ? or '.$clientDataTable.'.data like ?)', [
                            '%'.$searchTerm.'%',
                            '%'.$searchTerm.'%',
                            '%'.$searchTerm.'%'
                        ])->count($clientTable.'.'.$this->getKeyName());
    }

    public function scopeBySearchTerm($query, $searchTerm) {

        $clientData = new \App\ClientData();
        $clientDataTable = $clientData->getTable();
        $clientTable = $this->getTable();

        return $query
                        ->leftJoin( $clientDataTable,
                                    $clientTable.'.'.$this->getKeyName(),
                                    '=',
                                    $clientDataTable.'.'.$this->getKeyName())
                        ->whereRaw('(emailaddress like ? or comment like ? or '.$clientDataTable.'.data like ?)', [
                            '%'.$searchTerm.'%',
                            '%'.$searchTerm.'%',
                            '%'.$searchTerm.'%'
                        ])
                        ->groupBy($clientTable.'.'.$this->getKeyName());
    }

    public function hasValidAge() {
        $secondsIn18Years = 567648000;
        $now = strtotime('now');
        $birthDate = $this->birthDate;

        if (is_string($birthDate)) {
            $birthDate = strtotime($birthDate);
        }

        // If birthdate is not valid we suppose he is over 18
        if (! is_numeric($birthDate)) {
            return true;
        }

        if (($now - $birthDate) > $secondsIn18Years) {
            return true;
        }

        return false;
    }

    public static function filterClientsWithValidAge($clients) {
        $filteredClients = [];

        foreach ($clients as $client) {
            if ($client->hasValidAge()) {
                $filteredClients[] = $client;
                continue;
            }

            $client->setAsUnsubscribed();
            $client->save();
        }

        return collect($filteredClients);
    }

}
