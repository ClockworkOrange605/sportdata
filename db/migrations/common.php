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