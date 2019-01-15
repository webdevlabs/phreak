<?php

namespace Modules\Eloq\Models;

use System\Language;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ArticleTranslation extends Eloquent
{
  public $timestamps = false;
  protected $guarded = ['id'];
  protected $table = 'article_translations';

  public function article()
  {
    return $this->belongsTo('Modules\Eloq\Models\Article');
  }

}
