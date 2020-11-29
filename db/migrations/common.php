<?php 
    use Illuminate\Database\Capsule\Manager as Capsule;

    Capsule::schema()->create('sports', function ($table) {
        $table->id();
        $table->string('name');
    });

    Capsule::schema()->create('countries', function ($table) {
        $table->id();
        $table->string('name');
    });

    Capsule::schema()->create('teams', function ($table) {
        $table->id();
        $table->string('name');
    });

    Capsule::schema()->create('leagues', function ($table) {
        $table->id();
        $table->foreignId('sport_id')->constrained('sports');
        $table->foreignId('country_id')->constrained('countries');
        $table->string('name');
    });

    Capsule::schema()->create('events', function ($table) {
        $table->id();
        $table->foreignId('league_id')->constrained('leagues');
        $table->foreignId('home_team_id')->constrained('teams');
        $table->foreignId('away_team_id')->constrained('teams');
        $table->string('status');
        $table->timestamp('date');
        $table->integer('home_score')->nullable();
        $table->integer('away_score')->nullable();
    });

    Capsule::schema()->create('odd_types', function ($table) {
        $table->id();
        $table->string('name');
    });

    Capsule::schema()->create('odds', function ($table) {
        $table->id();
        $table->foreignId('event_id')->constrained('events');
        $table->foreignId('type_id')->constrained('odd_types');
        $table->string('name');
        $table->float('value');
        $table->boolean('is_winner')->nullable();
    });
