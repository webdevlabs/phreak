<?php

namespace Modules\Eloq\Controllers;

use Modules\Eloq\Models\Article as Article;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFoundException;

class ArticleController {
    
    public function getCreate ()
    {
        $article = new Article();
        $article->online = true;
        $article->save();

        foreach (['en', 'bg', 'fr', 'de'] as $locale) {
            $article->translation($locale)->name = "Title {$locale}";
            $article->translation($locale)->text = "Text {$locale}";
//            $article->translation($locale)->save();
        }        
        $article->save();
        echo 'Created an article with some translations!';
    }

    public function getShow($id)
    {
        try {
            $article = Article::find($id)->translation()->firstOrFail();
        }
        catch (NotFoundException $err) {
            echo 'not found';
            return;
        }
        print_r($article->toArray());
    }

}
