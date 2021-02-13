<?php

namespace Modules\Eloq\Controllers;

use Modules\Eloq\Models\Article as Article;
use Modules\Eloq\Models\ArticleTranslation as ArticleTranslation;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFoundException;

class ArticleController {
    
    public function getCreate ()
    {
        $article = new Article();
        $article->online = true;
        $article->save();

        foreach (['en', 'bg', 'fr', 'de'] as $locale) {            
            $translations[] = new ArticleTranslation([
                'name'=>"Title {$locale}",
                'text'=>"Text {$locale}",
                'locale'=>$locale
            ]);
        }        
        $article->translation()->saveMany($translations);
//        $article->translation()->saveMany([new ArticleTranslation(['name'=>'asd','text'=>'asdfx'])]);

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
