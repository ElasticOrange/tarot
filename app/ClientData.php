<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class ClientData extends Model
{

    protected $primaryKey = null;
    public $incrementing = false;

	protected $table = 'email_subscribers_data';


    public function client() {
        return $this->belongsTo('\App\Client', 'subscriberid', 'subscriberid');
    }

    public function field() {
        return $this->belongsTo('\App\ClientField', 'fieldid', 'fieldid');
    }

    public function save(array $options = array()) {
    	DB::delete('DELETE FROM '.$this->table.' WHERE subscriberid = '.$this->subscriberid.' AND fieldid = '.$this->fieldid);
    	return DB::insert('INSERT INTO '.$this->table.' (subscriberid, fieldid, data) VALUES (?, ?, ?)', [$this->subscriberid, $this->fieldid, $this->data]);
    }

}
