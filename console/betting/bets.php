<?php
    require __DIR__ . '/../../src/bootstrap.php';

    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Clients\BetBoom\Customer;
    use SportData\Models\Events\Event;
    use SportData\Models\Source;

    $source = Source::where('name', 'BetBoom')->first();
    $eventsClient = new BetBoom;
    $betClient = new Customer;

    $events = $eventsClient->getLiveEvents(1, [1, 748]);
    $events = $events->sortBy('period_time');
    // $events->each('dump_event');

    // $events = $events
    //     ->where('period_time', '=',  89)
    //     ->filter(
    //     function($event) {
    //         if(!empty($odds = $event->odds->where('source.id', 748)->first())) {
    //             if(!empty($odd = $odds->values->where('original.SC', 2)->first())) {
    //                 return $odd->value > 1.35;
    //             }
    //         }

    //         return false;
    //     }
    // );

    $events = $events
        ->where('period_time', '=', 50)
        // ->where('period_time', '>', 50)
        ->filter(
        function($event) {
            if(!empty($odds = $event->odds->where('source.id', 748)->first())) {
                if(!empty($odd = $odds->values->where('original.SC', 2)->first())) {
                    // return $odd->value > 4;
                    return $odd->value > 5;
                }
            }

            return false;
        }
    );

    dump('----------------------------------------------------');
    $events->each('dump_event');

    $events
    ->each(
        function($event) use($source, $betClient) {
            $added_event = Event::findBySourceId($source->id, $event->source->id);

            if(!empty($added_event)) {
                $odds = $event->odds->where('source.id', 748)->first();
                $odd = $odds->values->where('original.SC', 2)->first();

                // $bet = $betClient->makeBet($event->source->id, $odd->source->id, $odd->value, 1000);
                // $bet = $betClient->makeBet($event->source->id, $odd->source->id, $odd->value, 100);
                // dump($bet);


                // if(empty($bet->coupon_id)) {  
                //     $bet = $betClient->makeBet($event->source->id, $odd->source->id, $odd->value, 100);
                //     dump($bet);
                // }               
            }
        }
    );

    // $betClient->getOrders();

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