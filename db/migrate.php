<?php 
    require __DIR__ . '/../vendor/autoload.php';

    use Dotenv\Dotenv;

    Dotenv::createImmutable(__DIR__ .'/..')->load();

    use Illuminate\Database\Capsule\Manager as Capsule;

    $capsule = new Capsule;

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => env('DB_HOST', 'localhost'),
        'database'  => env('DB_NAME', 'database'),
        'username'  => env('DB_USER', 'root'),
        'password'  => env('DB_PASS', ''),
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    use Illuminate\Events\Dispatcher;
    use Illuminate\Container\Container;
    $capsule->setEventDispatcher(new Dispatcher(new Container));
    $capsule->setAsGlobal();

    Capsule::schema()->create('sports', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->string('title');
    });

    Capsule::schema()->create('countries', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->string('title');
    });

    Capsule::schema()->create('leagues', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->string('title');
    });

    Capsule::schema()->create('events', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->integer('country_id');
        $table->integer('sport_id');
        $table->integer('league_id');
        $table->string('title');
        $table->string('team1');
        $table->string('team2');
        $table->string('timestamp');
    });

    Capsule::schema()->create('odd_types', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->string('title');
    });

    Capsule::schema()->create('odds', function ($table) {
        $table->increments('id');
        $table->integer('odd_type_id');
        $table->integer('event_id');
        $table->integer('external_id')->unique();
        $table->string('title');
        $table->float('value');
    });

    