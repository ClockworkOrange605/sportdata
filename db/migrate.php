<?php 
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Database\Capsule\Manager as Capsule;

    Capsule::schema()->create('sports', function ($table) {
        $table->id();
        $table->unsignedBigInteger('external_id')->unique();
        $table->string('name');
    });

    Capsule::schema()->create('countries', function ($table) {
        $table->id();
        $table->unsignedBigInteger('external_id')->unique();
        $table->string('name');
    });

    Capsule::schema()->create('leagues', function ($table) {
        $table->id();
        $table->unsignedBigInteger('external_id')->unique();
        $table->string('name');
    });

    Capsule::schema()->create('events', function ($table) {
        $table->id();
        $table->unsignedBigInteger('external_id')->unique();
        $table->foreignId('sport_id')->constrained('sports');
        $table->foreignId('country_id')->constrained('countries');
        $table->foreignId('league_id')->constrained('leagues');
        $table->string('name');
        $table->string('team1');
        $table->string('team2');
        $table->timestamp('date');
    });

    Capsule::schema()->create('odd_types', function ($table) {
        $table->id();
        $table->unsignedBigInteger('external_id')->unique();
        $table->string('name');
    });

    Capsule::schema()->create('odds', function ($table) {
        $table->id();
        $table->unsignedBigInteger('external_id')->unique();
        $table->foreignId('event_id')->constrained('events');
        $table->foreignId('type_id')->constrained('odd_types');
        $table->string('name');
        $table->float('value');
    });

?>