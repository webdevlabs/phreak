<?php

namespace Modules\Eloq\Models;

use System\Language;
use Illuminate\Database\Eloquent\Model as Eloquent;

class CountryTranslation extends Eloquent {

    public $timestamps = false;
    protected $fillable = ['name'];

}
