<?php 
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Database\Capsule\Manager as Capsule;
    
    /* sources.php */
    Capsule::schema()->dropIfExists('sources_pivots');
    Capsule::schema()->dropIfExists('sources');

    /* events.php */
    // Capsule::schema()->dropIfExists('odds');
    // Capsule::schema()->dropIfExists('odd_types');
    Capsule::schema()->dropIfExists('events');
    
    /* common.php */
    Capsule::schema()->dropIfExists('teams');
    Capsule::schema()->dropIfExists('leagues');
    Capsule::schema()->dropIfExists('countries');
    Capsule::schema()->dropIfExists('sports');
