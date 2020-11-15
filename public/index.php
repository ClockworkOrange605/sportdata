<?php
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use SportData\Models\Sport;
    use SportData\Models\Event;

    $request = Request::capture();

    $events = Event::with(['sport', 'country', 'league', 
                            'odds', 'odds.type'])->get();

    $response = new Response($events);

    $response->send();
?>