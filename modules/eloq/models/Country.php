<?php

namespace Modules\Eloq\Models;

use System\Language;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Country extends Eloquent {
    
    use Translatable;
    
    public $translatedAttributes = ['name'];
    protected $fillable = ['code'];
    
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    // (optionaly)
    // protected $with = ['translations'];

}
