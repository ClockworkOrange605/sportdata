<?php 
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Database\Capsule\Manager as Capsule;

    Capsule::schema()->create('sports', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->string('name');
    });

    Capsule::schema()->create('countries', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->string('name');
    });

    Capsule::schema()->create('leagues', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->string('name');
    });

    Capsule::schema()->create('events', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->sports('sport_id')->references('id')->on('sports');
        $table->foreign('country_id')->references('id')->on('countries');        
        $table->integer('league_id')->references('id')->on('leagues');
        $table->string('name');
        $table->string('team1');
        $table->string('team2');
        $table->string('timestamp');
    });

    Capsule::schema()->create('odd_types', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->string('name');
    });

    Capsule::schema()->create('odds', function ($table) {
        $table->increments('id');
        $table->integer('external_id')->unique();
        $table->foreign('event_id')->refernces('id')->on('events');
        $table->integer('type_id')->references('id')->on('odd_types');
        $table->string('name');
        $table->float('value');
    });

    