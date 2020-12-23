<?php
    require __DIR__ . '/../../src/bootstrap.php';

    use Illuminate\Support\Carbon;
    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Clients\BetBoom\Customer;
    use SportData\Models\Events\Event;

    $eventsClient = new BetBoom;
    $betClient = new Customer;

    $events = $eventsClient->getLiveEvents(4, [702]);
    $events = $events->sortBy('period_name');
    // $events = $events->sortBy('period_time');
    // $events->each('dump_event');

    $events = $events
        // ->where('period_name', 'Not Started')
        ->filter(
        function($event) {
            if(!empty($odds = $event->odds->where('source.id', 702)->first())) {
                // if(!empty($odd = $odds->values->where('original.SC', 1)->first())) {}
                return true;
            }

            return false;
        }
    );

    $events->each('dump_event');

    $events
    ->each(
        function($event) {
            $odds = $event->odds->where('name', 'Result')->first();
            $odd1 = $odds->values->where('original.SC', 1)->first();
            $odd2 = $odds->values->where('original.SC', 2)->first();

            // dump($odds);
            dump($odd1->value, $odd2->value);
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

    dd(
        Carbon::now()->format('H:i') .' ('. (microtime(true) - SPORTDATA_START) .')'
    );