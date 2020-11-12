<?php 
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Database\Capsule\Manager as Capsule;
    
    Capsule::schema()->dropIfExists('odds');
    Capsule::schema()->dropIfExists('odd_types');
    Capsule::schema()->dropIfExists('events');
    Capsule::schema()->dropIfExists('sports');
    Capsule::schema()->dropIfExists('countries');
    Capsule::schema()->dropIfExists('leagues');
    

?>