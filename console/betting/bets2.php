<?php
    require __DIR__ . '/../../src/bootstrap.php';

    use Illuminate\Support\Carbon;
    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Clients\BetBoom\Betting;
    use SportData\Models\Events\Event;
    use SportData\Models\Betting\Signal;
    use SportData\Models\Source;

    $source = Source::where('name', 'BetBoom')->first();
    $eventsClient = new BetBoom;
    $betClient = new Betting;

    $events = $eventsClient->getLiveEvents(1, [1, 748]);
    $events = $events->sortBy('period_time');
    // $events->each('dump_event');

    $events = $events
        ->where('period_name', 'Not Started')
        ->filter(
        function($event) {
            if(!empty($odds = $event->odds->where('source.id', 1)->first())) {
                if(!empty($odd = $odds->values->where('original.SC', 1)->first())) {
                    return $odd->value > 3 && $odd->value < 5;
                }
            }

            return false;
        }
    );

    // dump('----------------------------------------------------');
    // $betClient->index();
    $events->each('dump_event');

    $events
    ->each(
        function($event) use($source, $betClient) {
            $added_event = Event::findBySourceId($source->id, $event->source->id);

            if(!empty($added_event)) {
                $odds = $event->odds->where('source.id', 1)->first();
                $odd = $odds->values->where('original.SC', 1)->first();

                $added_signal = Signal::firstOrCreate(
                    [
                        'sport_id' => 1,//$added_event->sport->id,
                        'event_id' => $added_event->id,
                        'flag' => 'test',
                        'code' => 'home_team_win_new',
                    ],
                    [                        
                        'odd_type' => $odds->name,
                        'odd_external_id' => $odd->source->id,
                        'odd_term' => (int) $odd->term,
                        'odd_value' => $odd->value,
                    ]
                );

                if($added_signal->wasRecentlyCreated) {
                    $bet = $betClient->makeBet(
                        $event->source->id, 
                        $odd->source->id, 
                        $odd->value, 
                        50
                    ); 

                    dump(
                        $bet,
                        [                        
                            'odd_type' => $odds->name,
                            'odd_external_id' => $odd->source->id,
                            'odd_term' => (int) $odd->term,
                            'odd_value' => $odd->value,
                        ]
                    );

                    if(empty($bet->coupon_id)) {
                        /// renew auth
                    }                    
                }

                dump(
                    // $odds, 
                    // $odd,
                    $added_signal->wasRecentlyCreated,
                    // $bet
                );
            } else {
                // dump();
            }
        }
    );

    // dump(
    //     $betClient->getOrders(
    //         "2020-12-15 00:00:00",
    //         "2020-12-15 23:59:59"
    //     )
    // );
    

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