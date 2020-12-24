<?php
    require __DIR__ . '/../../src/bootstrap.php';

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

    $events
        ->where('period_time', '=', 10)
        ->each(function($event) use($source) {
            prepareSignals2($event, $source, 'football_next_goal_no_one_10');
        });

    $events
        ->where('period_time', '=', 50)
        ->each(function($event) use($source) {
            prepareSignals2($event, $source, 'football_next_goal_no_one_50');
        });

    $events
        ->where('period_time', '=', 60)
        ->each(function($event) use($source) {
            prepareSignals2($event, $source, 'football_next_goal_no_one_60');
        });

    $events
        ->where('period_time', '=', 80)
        ->each(function($event) use($source) {
            prepareSignals2($event, $source, 'football_next_goal_no_one_80');
        });

    $events
        ->where('period_time', '=', 85)
        ->each(function($event) use($source) {
            prepareSignals2($event, $source, 'football_next_goal_no_one_85');
        });
    $events
        ->where('period_time', '=', 86)
        ->each(function($event) use($source) {
            prepareSignals2($event, $source, 'football_next_goal_no_one_86');
        });
    $events
        ->where('period_time', '=', 87)
        ->each(function($event) use($source) {
            prepareSignals2($event, $source, 'football_next_goal_no_one_87');
        });
    $events
        ->where('period_time', '=', 88)
        ->each(function($event) use($source) {
            prepareSignals2($event, $source, 'football_next_goal_no_one_88');
        });

    $events
        ->where('period_time', '=', 89)
        ->each(function($event) use($source) {
            prepareSignals2($event, $source, 'football_next_goal_no_one_89');
        });

    function prepareSignals2($event, $source, $code) {
        $added_event = Event::findBySourceId($source->id, $event->source->id);
            
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

    function create_signal($added_event, $odd, $odd_value, $code) {
        $added_signal = Signal::create([
            'sport_id' => 1,//$added_event->sport->id,
            'event_id' => $added_event->id,
            'flag' => 'test',
            'code' => $code,
            'odd_external_id' => $odd_value->source->id,
            'odd_type' => $odd->name,
            'odd_term' => $odd_value->term,
            'odd_value' => $odd_value->value,
        ]);
    }

    function dump_signal($added_event, $odd, $odd_value, $code) {
        dump([
            'sport_id' => 1,//$added_event->sport->id,
            'event_id' => $added_event->id,
            'flag' => 'test',
            'code' => $code,
            'odd_external_id' => $odd_value->source->id,
            'odd_type' => $odd->name,
            'odd_term' => $odd_value->term,
            'odd_value' => $odd_value->value,
        ]);
    }

    dd(microtime(true) - SPORTDATA_START);