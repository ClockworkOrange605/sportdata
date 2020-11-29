<?php 
    use Illuminate\Database\Capsule\Manager as Capsule;

    Capsule::schema()->create('sources', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('baseurl');
        $table->string('apikey');
    });

    Capsule::schema()->create('sources_pivot', function ($table) {
        $table->foreignId('source_id');
        $table->foreignId('entity_id');
        $table->string('source_type');
        $table->string('entity_type');
        $table->string('source_key');
    });
    