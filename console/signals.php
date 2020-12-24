<?php
    require __DIR__ . '/../src/bootstrap.php';

    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Models\Source;
    use SportData\Models\Events\Event;
    use SportData\Models\Betting\Signal;

    $source = Source::where('name', 'BetBoom')->first();
    $client = new BetBoom;

    $sport_id = 1;
    $odd_types = [ 748 ];

    $events = $client->getLiveEvents(
        $sport_id, $odd_types
    );

    $events = $events->sortBy('period_time');
    $events->each('dump_event');

    $events
        ->where('period_time', '=', 80)
        ->each(function($event) use($source) {
            $added_event = Event::findBySourceId($source->id, $event->source->id);

            if(!empty($added_event) && !$event->odds->isEmpty()) {
                dump(
                    [
                        'sport_id' => 1,//$added_event->sport->id,
                        'event_id' => $added_event->id,
                        'flag' => 'test',
                        'code' => 'football_next_goal_80',
                        'odd_external_id' => $event->odds->first()->values->get(2)->source->id,
                        'odd_type' => $event->odds->first()->name,
                        'odd_term' => $event->odds->first()->values->get(2)->term,
                        'odd_value' => $event->odds->first()->values->get(2)->value,
                    ], 
                    $event->odds->first(),
                );

                $added_signal = Signal::create([
                    'sport_id' => 1,//$added_event->sport->id,
                    'event_id' => $added_event->id,
                    'flag' => 'test',
                    'code' => 'football_next_goal_80',
                    'odd_external_id' => $event->odds->first()->values->get(2)->source->id,
                    'odd_type' => $event->odds->first()->name,
                    'odd_term' => $event->odds->first()->values->get(2)->term,
                    'odd_value' => $event->odds->first()->values->get(2)->value,
                ]);
            }
        });

    function dump_event($event) {
        dump(
            ' ['. $event->teams->home->score .':'. $event->teams->away->score .'] '.
            $event->period_name . ' ('. $event->period_time .') '. 
            '🏆 ['. $event->league->source->id .']'. $event->league->name .
            ' ⚽ ['. $event->source->id .']'. $event->name
        );

        $event->odds->each(function($odd) {
            dump(
                $odd->name .': '. $odd->values->implode('value', ' | ')
            );
        });
    }

    dd(microtime(true) - SPORTDATA_START);