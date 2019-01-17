<?php

namespace Modules\Eloq\Controllers;

use Modules\Eloq\Models\Country as Country;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFoundException;

class CountryController {
    
    public function getCreate ()
    {
        $country = new Country();
        $country->code = 'BG';
        $country->save();

        foreach (['en', 'bg', 'fr', 'de'] as $locale) {
            $country->translateOrNew($locale)->name = "Title {$locale}";
        }
        $country->save();
    
        echo 'Created country with some translations!';
    }

    public function getShow($code)
    {
        try {
            $country = Country::where('code',$code)->firstOrFail();
        }
        catch (NotFoundException $err) {
            echo 'Code not found';
            return;
        }
        print_r($country->toArray());
    }

}
