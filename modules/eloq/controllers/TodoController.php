<?php

namespace Modules\Eloq\Controllers;


use Illuminate\Database\Capsule\Manager as Capsule;

class TodoController {

    public function anyIndex ()
    {
        Capsule::schema()->create('todos', function ($table) {
            $table->increments('id');
            $table->string('todo');
            $table->string('description');
            $table->string('category');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });     
    }

}
