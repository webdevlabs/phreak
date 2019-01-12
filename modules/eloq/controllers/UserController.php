<?php

namespace Modules\Eloq\Controllers;

use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\Eloq\Models\User as User;

class UserController {
    
    public function getCreatetable ()
    {
        Capsule::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('userimage')->nullable();
            $table->string('api_key')->nullable()->unique();
            $table->rememberToken();
            $table->timestamps();
        });
    }

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
        $user = User::where('email',$email)->first();
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
