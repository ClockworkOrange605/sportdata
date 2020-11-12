<?php
    require __DIR__ . '/../vendor/autoload.php';

    use Illuminate\Events\Dispatcher;
    use Illuminate\Container\Container;
    use Illuminate\Database\Capsule\Manager as Capsule;
    use Dotenv\Dotenv;

    Dotenv::createImmutable(__DIR__ .'/..')->load();

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

    $capsule->setEventDispatcher(new Dispatcher(new Container));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

?>