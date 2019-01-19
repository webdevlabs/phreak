# Phreak!
[![Build Status](https://travis-ci.org/webdevlabs/phreak.svg?branch=master)](https://travis-ci.org/webdevlabs/phreak)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/45799a2694d74bc784c62a89d24c9b5a)](https://www.codacy.com/app/webdevlabs/phreak?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=webdevlabs/phreak&amp;utm_campaign=Badge_Grade)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bd0c18b6-3e25-4c13-8969-7d28bc41eaf3/mini.png)](https://insight.sensiolabs.com/projects/bd0c18b6-3e25-4c13-8969-7d28bc41eaf3)
[![StyleCI](https://styleci.io/repos/86944823/shield?branch=master)](https://styleci.io/repos/86944823)

### ultra-light fast php framework powered by:
- [Phroute](https://github.com/mrjgreen/phroute) (url routing)
- [PHP-DI](https://github.com/PHP-DI/PHP-DI) (dependency injection container)
- [Smarty](https://github.com/smarty-php/smarty) (template engine)
- [Stash](https://github.com/tedious/www.stashphp.com) (caching library)
- [Zend Diactoros](https://github.com/zendframework/zend-diactoros) (request/response handlers)
- [RelayPHP](http://relayphp.com/) (middleware dispatcher)
- [PSR7-Middlewares](https://github.com/oscarotero/psr7-middlewares) (collection of PSR-7 middlewares)

### Installation
`composer create-project webdevlabs/phreak`

#### Serve app localy with the built-in PHP web server
`php -S localhost:8000 -t public`


### Autoloader
Very simple and understandable using the **Namespace** as file path. All directory names are converted to lowercase, file cases are kept as written.
Example:
```
<?php
use App\Controllers\Front;
```
will autoload the file *<phreakDir>*\app\controllers\Front.php

### Simple Routing
**Calls the App\Controllers\User::displayUser($id) method with {id} parameter as an argument**
```
$router->get('/users/{id}', ['App\Controllers\User','displayUser']);
```

### Controller Routing
**Calls the App\Controllers\Front.php with the proper request method.**
```
$router->controller('/', 'App\Controllers\Front');    
```
- GET request on http://host/login will call the App\Controllers\Front::getLogin() method.
- POST request on http://host/login will call the App\Controllers\Front::postLogin() method.
- GET request on http://host/article/some-article-uri will call the App\Controllers\Front::getArticle($uri) method with $uri parameter as an argument.

### Advanced Routing

#### Filters
**Calls class `App\Models\Auth` with method `checkLogin()` and break on `return false`.**
```
$router->filter('auth', ['App\Models\Auth','checkLogin']);
```
**Group all requests under http://host/profile/ through filter**
```
$router->group([
        'prefix'=>'profile', 
        'before'=>'auth'
    ], 
    function ($router) {
        $router->controller('/', 'App\Controllers\Account\Profile');    
    });
```
More advanced routings can be found at [Phroute's](https://github.com/mrjgreen/phroute) page.

### Validation
```
    public function postComment () 
    {
        $input  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $val = new \System\Validation($input);
        $val->addRule('name', 'Empty name field', ['required']);
        $val->addRule('comment', 'Empty comment field', ['required','minLength=5']);
        $val->addRule('comment', 'Comment too big', ['maxLength=500']);
        if ($val->validate()) {
            echo 'Comment post ok';
        } else {
            $this->template->assign('errors',$val->getErrors());
            $this->template->display("errors.tpl");
        }        
    }
```

## Simple controller
```
<?php

namespace App\Controllers;

use System\Template;
use System\Language;

class Front
{
    private $template;
    private $language;

    public function __construct (Template $template, Language $language) 
    {
        $this->template = $template;
        $this->language = $language;
    }

   public function getIndex()
    {
        $this->template->assign('title', 'Phreak!');
        $this->template->assign('languages', $this->language->available_languages);  
        $this->template->display('layout.tpl');
    }    
}
```
*More advanced examples can be found in the 'modules' directory.*

## Events
```
use System\Event;
class ... {
    public function __construct (Event $event) 
    {
        $this->event = $event;
        $this->event->bind('someEventName', function () { echo "stay foolish"; });
    }
    public function getSome () {
        $this->event->trigger('someEventName');
    }
}
```

### Database
You now have different options for managing your database. There are 2 existing database drivers.
Sample database wrapper class DB:: and the well known database mapper Eloquent.

**You control what database driver to load directly from config/database.php**

Example PDO driver fetch queries:
```
 * Result: single column
 * $count = DB::column('SELECT COUNT(*) FROM `user`);

 * Result: an array(key => value) results (i.e. for making a selectbox)
 * $pairs = DB::pairs('SELECT `id`, `username` FROM `user`);

 * Result: a single row result
 * $user = DB::row('SELECT * FROM `user` WHERE `id` = ?', array($user_id));

 * Result: a single row result
 * $user = DB::row('SELECT * FROM `user` WHERE `id` = :varname', array(":varname"=>"some variable"));

 * Result: an array of results
 * $banned_users = DB::fetch('SELECT * FROM `user` WHERE `banned` = ?', array(TRUE));
 
 * Result: any query
 * $dosql = DB::query('UPDATE `settigs` WHERE `setid`=?', ['somesetid']);
 
 * Result: insert user
 * DB::insert('users', ['username'=>'test','password'=>'testpass']);

 * Result: update user password where user_id = 1
 * DB::update('users', ['password'=>'testpass'], '1', 'user_id');
 ```
 
 The new available method for managing your database is Eloquent (from the Laravel framework).
 
 The *Eloquent ORM* provides a beautiful, simple ActiveRecord implementation for working with your database. Each database table has a corresponding "Model" which is used to interact with that table. Models allow you to query for data in your tables, as well as insert new records into the table.
 
*Examples of phreak eloquent usage can be found at the '**modules/elog**' made for demonstration.*

Full documentation can be found at [Eloquent's](https://laravel.com/docs/5.7/eloquent) page.


### Console Commands
You can also execute phreak controllers from your console/crontab by calling the console.php file where the parameters are the filename of the controller.

**Calls the App\Commands\SomeController.php with the proper request method.**
```
php console.php SomeController
```
All console controllers are located in the "app/commands" folder. The folder location is defined in the 'app/ConsoleController.php' and can be changed. 
