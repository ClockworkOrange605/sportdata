<?php 
    use Illuminate\Database\Capsule\Manager as Capsule;

    Capsule::schema()->create('events', function ($table) {
        $table->id();
        $table->foreignId('league_id')->constrained('leagues');
        $table->foreignId('home_team_id')->constrained('teams');
        $table->foreignId('away_team_id')->constrained('teams');
        $table->string('name');
        $table->string('status');
        $table->integer('home_score')->nullable();
        $table->integer('away_score')->nullable();
        $table->dateTime('start_at');

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
