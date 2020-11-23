<?php
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Http\Response;
    use Illuminate\Database\Capsule\Manager as Capsule;
    use \League\Csv\Writer as Csv;

    $csv = Csv::createFromFileObject(new \SplTempFileObject);
    $events = Capsule::select("select e.id,
            s.name as sport, c.name as country, l.name as leaugue,
            e.name, e.home_team, e.away_team, e.date,
            e.score, e.home_score, e.away_score,
            o1.is_winner as 'is_w1', o1.value as 'w1', 
            o2.is_winner as 'is_w2', o2.value as 'w2', 
            ox.is_winner as 'is_wx', ox.value as 'wx'            
        from events e
            left join sports s on e.sport_id = s.id
            left join countries c on e.country_id = c.id
            left join leagues l on e.league_id = l.id
            left join odds o1 on e.id = o1.event_id and o1.type_id = 1 and o1.name = 'Win1'
            left join odds o2 on e.id = o2.event_id and o2.type_id = 1 and o2.name = 'Win2'
            left join odds ox on e.id = ox.event_id and ox.type_id = 1 and ox.name = 'X'
        order by e.date desc;");

    foreach ($events as $event) {
        $csv->insertOne((array) $event);
    }

    $response = new Response((string) $csv, 200, [
        'Content-Type' => 'text/csv',
        'Content-Transfer-Encoding' => 'binary',
        'Content-Disposition' => 'attachment; filename="events.csv"',
    ]);

    $response->send();

?>