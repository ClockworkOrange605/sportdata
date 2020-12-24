<?php
    require __DIR__ . '/../../src/bootstrap.php';

    use Illuminate\Support\Carbon;
    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Clients\BetBoom\Customer;
    use SportData\Models\Events\Event;
    use SportData\Models\Betting\Signal;
    use SportData\Models\Source;

    $source = Source::where('name', 'BetBoom')->first();
    $eventsClient = new BetBoom;
    $betClient = new Customer;

    $events = $eventsClient->getLiveEvents(1, [1, 748]);
    $events = $events->sortBy('period_time');
    // $events->each('dump_event');

    $events = $events
        // ->where('period_time', '=', 50)
        ->filter(
        function($event) {
            if(!empty($odds = $event->odds->where('source.id', 748)->first())) {
                if(!empty($odd = $odds->values->where('original.SC', 2)->first())) {
                    return $odd->value > 5 && $odd->term > 1;
                }
            }

            return false;
        }
    );

    // dump('----------------------------------------------------');
    $events->each('dump_event');

    $events
    ->each(
        function($event) use($source, $betClient) {
            $added_event = Event::findBySourceId($source->id, $event->source->id);

            if(!empty($added_event)) {
                $odds = $event->odds->where('source.id', 748)->first();
                $odd = $odds->values->where('original.SC', 2)->first();

                dump(
                    [                        
                        'odd_type' => $odds->name,
                        'odd_external_id' => $odd->source->id,
                        'odd_term' => (int) $odd->term,
                        'odd_value' => $odd->value,
                    ]
                );

                // $added_signal = Signal::firstOrCreate(
                //     [
                //         'sport_id' => 1,//$added_event->sport->id,
                //         'event_id' => $added_event->id,
                //         'flag' => 'test',
                //         'code' => 'home_rest_noone',
                //     ],
                //     [                        
                //         'odd_type' => $odds->name,
                //         'odd_external_id' => $odd->source->id,
                //         'odd_term' => (int) $odd->term,
                //         'odd_value' => $odd->value,
                //     ]
                // );

                // if($added_signal->wasRecentlyCreated) {
                    // $bet = $betClient->makeBet(
                    //     $event->source->id, 
                    //     $odd->source->id, 
                    //     $odd->value, 
                    //     5000
                    // ); 

                    // dump($bet);

                    // if(empty($bet->coupon_id)) {
                    //     /// renew auth
                    // }                    
                // }
            } else {
                // dump();
            }
        }
    );

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

    dd(
        Carbon::now()->format('H:i') .' ('. (microtime(true) - SPORTDATA_START) .')'
    );