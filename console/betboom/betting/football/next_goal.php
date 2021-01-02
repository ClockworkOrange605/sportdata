<?php   
    require __DIR__ . '/../../../../src/bootstrap.php';

    use Illuminate\Support\Str;
    use Illuminate\Support\Carbon;

    use SportData\Clients\BetBoom\Events as EventsClient;
    use SportData\Clients\BetBoom\Betting as BettingClient;

    use SportData\Models\Source;
    use SportData\Models\Common\Sport;

    $source = Source::where('name', 'BetBoom')->first();
    $sport = Sport::where('name', 'Football')->first();

    $client = new EventsClient;
    $events = $client->getLiveEvents($sport->getExternalId($source->id), [748, 682]);
    $events = $events->sortBy('period.time');

    # No One
    $events
        ->where('period.time', 60)
        ->where('teams.home.score', 0)
        ->where('teams.away.score', 0)
        ->each(function($event) { 
            printEvent($event);
            $odds = $event->odds->where('name', 'Next Goal')->first();
            if(!empty($odds)) {
                $odd = $odds->values->filter(function($item) {
                    return Str::startsWith($item->name, 'No One');
                })->first();
                if(!empty($odd)) {
                    printOdd($odd);
                    if(
                        $odd->condition < 2 
                     && $odd->value > 2
                    ) {
                        $bet = makeBet($event, $odd);
                        printBet($bet);
                    }
                }
            }
        });

    function printEvent($event) {
        dump(
            '',
            '🏆 '. $event->league->name,
            '⚽ '. $event->name,
            ' '. $event->livescores_id,
            '🥅 '. $event->scores,
            '⏰ '. $event->period->name . ' ' . $event->period->time,
        );
    }

    function printOdd($odd) {
        dump(
            '🎫 '. $odd->name . ' ' . $odd->value,
        );
    }

    function printBet($bet) {
        dump(
            $bet
        );
    }

    // +320+330-100-100-100-100-100+213+207-100+220+215-100+223-100+205+207-100-100-100+223+262+360-100-100+223-100-100+230-100+290+285
    
    function makeBet($event, $odd) {
        $client = new BettingClient;
        $betAmount = 50;

        $bet = $client->makeBet($event->id, $odd->id, $odd->value, $betAmount);
        return $bet;
    }

    dd(
        Carbon::now()->format('H:i') .' ('. (microtime(true) - SPORTDATA_START) .')'
    );