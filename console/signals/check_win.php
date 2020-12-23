<?php
    require __DIR__ . '/../../src/bootstrap.php';

    use SportData\Models\Betting\Signal;
    use Illuminate\Support\Carbon;

    $stake = 50; $profit = 0;
    $signals = Signal::with('event')

        // ->where('created_at', '>', '2020-12-14')
        // ->where('created_at', '<', '2020-12-15')

        // ->where('created_at', '>', '2020-12-15')
        // ->where('created_at', '<', '2020-12-16')

        // ->where('created_at', '>', '2020-12-16')
        // ->where('created_at', '<', '2020-12-17')

        // ->where('created_at', '>', '2020-12-17')
        // ->where('created_at', '<', '2020-12-18')

        // ->where('created_at', '>', '2020-12-18')
        // ->where('created_at', '<', '2020-12-19')

        // ->where('created_at', '>', '2020-12-19')
        // ->where('created_at', '<', '2020-12-20')

        // ->where('created_at', '>', '2020-12-20')
        // ->where('created_at', '<', '2020-12-21')

        // ->where('created_at', '>', '2020-12-21')
        // ->where('created_at', '<', '2020-12-22')

        // ->where('created_at', '<', (string) Carbon::yesterday()->startOfDay())

        // ->where('created_at', '<', (string) Carbon::now()->startOfDay())
        // ->where('created_at', '>', (string) Carbon::yesterday()->startOfDay())

        ->where('created_at', '>', (string) Carbon::now()->startOfDay())

        // ->where('code', 'home_team_win_new')
        ->where('code', 'football_result_home_team')
        // ->where('code', 'football_result_away_team')
        // ->where('code', 'football_result_no_one')

        ->where('odd_type', 'Result')
        // ->where('odd_value', '<', 1.21)
        ->where('odd_value', '>', 3)
        // ->where('odd_value', '>', 4)
        // ->where('odd_value', '<', 4)
        ->where('odd_value', '<', 5)
        // ->where('odd_value', '<', 6)
        // ->where('odd_value', '<', 10)
        
        ->get()->unique('event_id');

    // $odd = 1;
    // $signals->each(function($item) use(&$odd) {
    //     $odd *= $item->odd_value;
    // });
    // dump($odd);

    $signals
        // ->where('event.status', 'finished')
        ->each(function($signal) use(&$profit, $stake) {
            $profit -= $stake;

            if(
                $signal->event->status == 'finished' && 
                ($signal->event->home_score > $signal->event->away_score)
            ) {
                $profit += $signal->odd_value * $stake;
            } elseif(
                $signal->event->status == 'finished' && 
                ($signal->event->home_score + $signal->event->away_score)
            ) {
                // $profit -= $stake;
            }

        $res = $signal->event->status == 'finished' ?
            ($signal->event->home_score > $signal->event->away_score)? 
                "+ ". ($signal->odd_value * $stake)
            : "- {$stake}"
        : '? ???';

        dump(
            // $signal->toArray(),
            "{$signal->created_at} ".
            "{$res} [{$signal->id}] ".
            "{$signal->odd_type} ({$signal->odd_value}) 🏆 {$signal->event->league->name} ⚽ {$signal->event->name} [{$signal->event->home_score}:{$signal->event->away_score}]"
        );
    });

    dump("Profit: {$profit}₽");
    dump("Bets: ". ($signals->count()*$stake));
    dump("Bets count: {$signals->count()}");

    dd(microtime(true) - SPORTDATA_START);