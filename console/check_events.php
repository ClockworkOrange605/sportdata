<?php
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Support\Carbon;
    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Models\Source;

    $client = new BetBoom;
    $source = Source::where('name', 'Betboom')->first();

    dump((string) Carbon::now()->subHours(2));

    $source->events()
        ->where('status', 'not_started')
        ->where('start_at', '<', (string) Carbon::now()->subHours(2))
        ->latest('start_at')
        ->get()
        ->each(function($event) use($client) {
            // dump($event);
            $response = $client->getEvents(
                $event->league->country->sport->sources->first()->pivot->external_id,
                $event->league->country->sources->first()->pivot->external_id,
                $event->league->sources->first()->pivot->external_id, 
                (string) Carbon::parse($event->start_at)->subDays(1),
                (string) Carbon::parse($event->start_at)->addDays(1),
            );

            $source_events = $response
                ->where('league.source.id', $event->league->sources->first()->pivot->external_id)
                ->first();

            if(!empty($source_events)) {
                $source_event = $source_events->events
                    ->where('source.id', $event->pivot->external_id)
                    ->first();

                if(!empty($source_event)) {
                    $event->update(
                        [
                            'status' => $source_event->status,
                            'home_score' => $source_event->teams->home->score,
                            'away_score' => $source_event->teams->away->score,
                        ]
                    );

                    dump(
                        [
                            'status' => $source_event->status,
                            'home_score' => $source_event->teams->home->score,
                            'away_score' => $source_event->teams->away->score,
                        ]
                    );        
                }
            }            
        });

    dd(microtime(true) - SPORTDATA_START);