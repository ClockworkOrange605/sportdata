<?php 
    require __DIR__ . '/../vendor/autoload.php';

    use Dotenv\Dotenv;

    Dotenv::createImmutable(__DIR__ .'/..')->load();

    use Illuminate\Database\Capsule\Manager as Capsule;

    $capsule = new Capsule;

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => env('DB_HOST'),
        'database'  => env('DB_NAME'),
        'username'  => env('DB_USER'),
        'password'  => env('DB_PASS'),
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    use Illuminate\Events\Dispatcher;
    use Illuminate\Container\Container;
    $capsule->setEventDispatcher(new Dispatcher(new Container));
    $capsule->setAsGlobal();

    Capsule::schema()->dropIfExists('sports');
    Capsule::schema()->dropIfExists('countries');
    Capsule::schema()->dropIfExists('leagues');
    Capsule::schema()->dropIfExists('events');
    Capsule::schema()->dropIfExists('odd_types');
    Capsule::schema()->dropIfExists('odds');