<?php
use App\Middleware\AuthMiddleware;


// Register middleware auth
app()->registerMiddleware('auth', function () {

    $middleware = new AuthMiddleware;
    return $middleware->call();

});


//End-point autenticación
app()->post('/login', 'AuthsController@login');

app()->group('/api', ['middleware' => 'auth', function () {

    //End-points Users
    app()->get('/users', 'UsersController@index');
    app()->get('/users/{id}', 'UsersController@show');
    app()->post('/users', 'UsersController@store');
    app()->put('/users/{id}', 'UsersController@update');
    app()->delete('/users/{id}', 'UsersController@destroy');

    //End-points Properties
    app()->get('/properties', 'PropertiesController@index');
    app()->get('/properties/{id}','PropertiesController@show');
    app()->post('/properties','PropertiesController@store');
    app()->put('/properties/{id}','PropertiesController@update');
    app()->delete('/properties/{id}','PropertiesController@destroy');

    //End-points Blog
    app()->get('/blog','BlogsController@index');
    app()->get('/blog/{id}','BlogsController@show');
    app()->post('/blog','BlogsController@store');
    app()->put('/blog/{id}','BlogsController@update');
    app()->delete('/blog/{id}','BlogsController@destroy');

}]);

