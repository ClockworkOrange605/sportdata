<?php
    require __DIR__ . '/../src/bootstrap.php';

    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Models\Source;
    use SportData\Models\Common\Team;
    use SportData\Models\Events\Event;

    $source = Source::where('name', 'BetBoom')->first();
    $client = new BetBoom;

    $source->leagues->each(function($league) use($client, $source){
        $events = $client->getPrematchEvents($league->pivot->external_id);
        $events->each(function($event) use($league, $source){
            $added_home_team = Team::firstOrCreate(
                [
                    'league_id' => $league->id,
                    'name' => $event->teams->home->name,
                ]
            );
            $added_away_team = Team::firstOrCreate(
                [
                    'league_id' => $league->id,
                    'name' => $event->teams->away->name,
                ]
            );
    
            $added_event = Event::firstOrCreate(
                [
                    'league_id' => $league->id,
                    'name' => $event->name,
                    'start_at' =>$event->date
                ],
                [
                    'home_team_id' => $added_home_team->id,
                    'away_team_id' => $added_away_team->id,
                    'status' => 'not_started'
                ]
            );
    
            $source->events()->syncWithoutDetaching([
                $added_event->id => ['external_id' => $event->source->id]
            ]);
        });
    });

    dd(microtime(true) - SPORTDATA_START);