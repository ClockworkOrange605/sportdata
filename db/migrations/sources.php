<?php 
    use Illuminate\Database\Capsule\Manager as Capsule;

    Capsule::schema()->create('sources', function ($table) {
        $table->id();
        $table->string('name');
    });

    Capsule::schema()->create('sources_pivots', function ($table) {
        $table->foreignId('source_id');
        $table->foreignId('entity_id');
        $table->integer('external_id');
        $table->string('sources_pivot_type');

        $table->unique(['source_id', 'entity_id', 'sources_pivot_type']);
    });
    