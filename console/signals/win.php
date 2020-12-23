<?php
    require __DIR__ . '/../../src/bootstrap.php';

    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Models\Source;
    use SportData\Models\Events\Event;
    use SportData\Models\Betting\Signal;

    $source = Source::where('name', 'BetBoom')->first();
    $client = new BetBoom;

    $sport_id = 1;
    $odd_types = [ 1 ];

    $events = $client->getLiveEvents(
        $sport_id, $odd_types
    );

    $events = $events->sortBy('period_time');

    $events
        ->where('period_time', '=', 0)
        ->each(function($event) use($source) {
            prepareSignals0($event, $source, 'football_result_home_team');
            prepareSignals1($event, $source, 'football_result_no_one');
            prepareSignals2($event, $source, 'football_result_away_team');
        });

    function prepareSignals0($event, $source, $code) {
        $added_event = Event::findBySourceId($event->source->id, $source->id);
            
        if(!empty($added_event) && !$event->odds->isEmpty()) {
            dump_event($event);

            dump_signal(
                $added_event, 
                $event->odds->first(), 
                $event->odds->first()->values->get(0),
                $code
            );

            create_signal(
                $added_event,
                $event->odds->first(), 
                $event->odds->first()->values->get(0),
                $code
            );
        }            
    }

    function prepareSignals1($event, $source, $code) {
        $added_event = Event::findBySourceId($event->source->id, $source->id);
            
        if(!empty($added_event) && !$event->odds->isEmpty()) {
            dump_event($event);

            dump_signal(
                $added_event, 
                $event->odds->first(), 
                $event->odds->first()->values->get(1),
                $code
            );

            create_signal(
                $added_event,
                $event->odds->first(), 
                $event->odds->first()->values->get(1),
                $code
            );
        }            
    }

    function prepareSignals2($event, $source, $code) {
        $added_event = Event::findBySourceId($event->source->id, $source->id);
            
        if(!empty($added_event) && !$event->odds->isEmpty()) {
            dump_event($event);

            dump_signal(
                $added_event, 
                $event->odds->first(), 
                $event->odds->first()->values->get(2),
                $code
            );

            create_signal(
                $added_event,
                $event->odds->first(), 
                $event->odds->first()->values->get(2),
                $code
            );
        }            
    }

    function create_signal($added_event, $odd, $odd_value, $code) {
        // if(!empty($added_event) && !$event->odds->isEmpty()) {
            $added_signal = Signal::create([
                'sport_id' => 1,//$added_event->sport->id,
                'event_id' => $added_event->id,
                'flag' => 'test',
                'code' => $code,
                'odd_external_id' => $odd_value->source->id,
                'odd_type' => $odd->name,
                'odd_term' => 0,
                'odd_value' => $odd_value->value,
            ]);
        // }
    }

    function dump_signal($added_event, $odd, $odd_value, $code) {
        dump([
            'sport_id' => 1,//$added_event->sport->id,
            'event_id' => $added_event->id,
            'flag' => 'test',
            'code' => $code,
            'odd_external_id' => $odd_value->source->id,
            'odd_type' => $odd->name,
            'odd_term' => 0,
            'odd_value' => $odd_value->value,
        ]);
    }

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