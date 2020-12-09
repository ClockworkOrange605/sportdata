<?php 
    use Illuminate\Database\Capsule\Manager as Capsule;

    Capsule::schema()->create('signals', function($table) {
        $table->id();

        $table->foreignId('sport_id');
        $table->foreignId('event_id');

        $table->string('flag');
        $table->string('type');

        $table->string('event_name');
        $table->string('odd_type');
        $table->string('odd_value');

        $table->timestamps();
    });