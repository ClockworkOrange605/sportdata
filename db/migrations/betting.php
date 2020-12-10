<?php 
    use Illuminate\Database\Capsule\Manager as Capsule;

    Capsule::schema()->create('signals', function($table) {
        $table->id();
        $table->foreignId('sport_id');
        $table->foreignId('event_id');
        $table->string('flag');
        $table->string('code');
        $table->foreignId('odd_external_id');
        $table->string('odd_type');
        $table->string('odd_term');
        $table->string('odd_value');

        $table->timestamps();
    });