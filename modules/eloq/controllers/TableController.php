<?php

namespace Modules\Eloq\Controllers;

use Illuminate\Database\Capsule\Manager as Capsule;

class TableController {

    public function anyIndex()
    {
        $this->createUsers();
        $this->createTodos();
    }

    public function getCreatecountries()
    {
        Capsule::schema()->create('countries', function($table)
        {
            $table->increments('id');
            $table->string('code');
            $table->timestamps();
        });
        
        Capsule::schema()->create('country_translations', function($table)
        {
            $table->increments('id');
            $table->integer('country_id')->unsigned();
            $table->string('name');
            $table->string('locale')->index();
        
            $table->unique(['country_id','locale']);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });        
        echo 'success';
    }

    public function getCreatearticles()
    {
        Capsule::schema()->create('articles', function ($table) {
            $table->increments('id');
            $table->boolean('online');
            $table->timestamps();
        });
        
        Capsule::schema()->create('article_translations', function ($table) {
//            $table->increments('id');
            $table->integer('article_id')->unsigned();
            $table->string('locale')->index();
        
            $table->string('name');
            $table->text('text');
        
            $table->unique(['article_id','locale']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');        
        });        
    }

    public function createUsers ()
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

    public function createTodos ()
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
