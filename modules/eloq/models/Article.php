<?php

namespace Modules\Eloq\Models;

use System\Language;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Article extends Eloquent
{
    protected $guarded = ['id'];

    public function translation($language=null)
    {
      if ($language == null) {
        $language = 'bg';
      }
//      echo $language.'<br/>';
      return $this->hasMany('Modules\Eloq\Models\ArticleTranslation')->where('locale', '=', $language);
   }

 }
