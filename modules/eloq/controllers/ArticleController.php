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
        }
        $article->translation('bg')->name = "Title bg";
        $article->translation('bg')->text = "Text bg";
        $article->save();
    
        echo 'Created an article with some translations!';
    }

    public function getShow($email)
    {
        try {
            $user = Article::where('email',$email)->firstOrFail();
        }
        catch (NotFoundException $err) {
            echo 'Email not found';
            return;
        }
        print_r($user->toArray());
    }

}
