<?php 
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Database\Capsule\Manager as Capsule;
    
    /* betting.php */
    Capsule::schema()->dropIfExists('signals');

    /* events.php */
    Capsule::schema()->dropIfExists('event_odds');
    Capsule::schema()->dropIfExists('events');
    
    /* common.php */
    Capsule::schema()->dropIfExists('odds');
    Capsule::schema()->dropIfExists('teams');
    Capsule::schema()->dropIfExists('leagues');
    Capsule::schema()->dropIfExists('countries');
    Capsule::schema()->dropIfExists('sports');

    /* sources.php */
    Capsule::schema()->dropIfExists('sources_pivots');
    Capsule::schema()->dropIfExists('sources');