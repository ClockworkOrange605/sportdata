<?php 
    use Illuminate\Database\Capsule\Manager as Capsule;

    Capsule::schema()->create('sports', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('code')->nullable();
        $table->string('icon')->nullable();

        $table->unique('name');
    });

    Capsule::schema()->create('countries', function ($table) {
        $table->id();
        $table->foreignId('sport_id')->constrained('sports');
        $table->string('name');
        $table->string('code')->nullable();
        $table->string('icon')->nullable();

        $table->unique(['sport_id', 'name']);
    });

    Capsule::schema()->create('leagues', function ($table) {
        $table->id();
        $table->foreignId('country_id')->constrained('countries');
        $table->string('name');
        $table->string('code')->nullable();
        $table->string('icon')->nullable();

        $table->unique(['country_id', 'name']);
    });

    Capsule::schema()->create('teams', function ($table) {
        $table->id();
        $table->foreignId('league_id')->constrained('leagues');
        $table->string('name');
        $table->string('code')->nullable();
        $table->string('icon')->nullable();

        $table->unique(['league_id', 'name']);
    });

    Capsule::schema()->create('events', function ($table) {
        $table->id();
        $table->foreignId('league_id')->constrained('leagues');
        $table->foreignId('home_team_id')->constrained('teams');
        $table->foreignId('away_team_id')->constrained('teams');
        $table->string('name');
        $table->string('status');
        $table->integer('home_score')->nullable();
        $table->integer('away_score')->nullable();
        $table->timestamp('start_at');

        $table->unique(['league_id', 'name', 'start_at']);
    });

    // Capsule::schema()->create('odd_types', function ($table) {
    //     $table->id();
    //     $table->string('name');
    // });

    // Capsule::schema()->create('odds', function ($table) {
    //     $table->id();
    //     $table->foreignId('type_id')->constrained('odd_types');
    //     $table->foreignId('event_id')->constrained('events');
    //     $table->string('name');
    //     $table->float('value');
    //     $table->float('condition')->nullable();
    //     $table->boolean('is_winner')->nullable();
    // });
