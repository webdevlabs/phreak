<?php

namespace Modules\Eloq\Controllers;

use Modules\Eloq\Models\User as User;
use Illuminate\Database\Eloquent\ModelNotFoundException as NotFoundException;

class UserController {
    
    public function getCreate ($email)
    {
        $user = User::Create([
            'name' => "Kshiitj Soni", 
            'email' => $email, 
            'password' => password_hash("1234",PASSWORD_BCRYPT)
            ]);
            echo 'success';
    }

    public function getShow($email)
    {
        try {
            $user = User::where('email',$email)->firstOrFail();
        }
        catch (NotFoundException $err) {
            echo 'Email not found';
            return;
        }
        print_r($user->toArray());
    }

    public function getCreateuser ()
    {
        $user = User::Create([
            'name' => "Kshiitj Soni", 
            'email' => "kshitij206@gmail.com", 
            'password' => password_hash("1234",PASSWORD_BCRYPT)
            ]);
    }
    
}
