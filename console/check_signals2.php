<?php
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Support\Carbon;
    use SportData\Models\Betting\Signal;

    $stake = 50; $profit = 0;
    $signals = Signal::with('event')
        ->where('code', 'football_next_goal_odd_9')
        ->where('odd_type', 'Next Goal')

        ->where('odd_value', '>', 9)        
        ->where('odd_value', '<', 20)
        // ->where('odd_term', '>', 1)
        ->where('odd_term', '<', 2)

        ->where('created_at', '>', '2020-12-14')
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

        // ->where('created_at', '<', (string) Carbon::now()->startOfDay())
        // ->where('created_at', '>', (string) Carbon::yesterday()->startOfDay())

        // ->where('created_at', '>', (string) Carbon::now()->startOfDay())
        ->get()->unique('event_id');

    $signals
        ->where('event.status', 'finished')
        ->each(function($signal) use(&$profit, $stake) {
            $profit -= $stake;

            if(
                $signal->event->status == 'finished' && 
                ($signal->event->home_score + $signal->event->away_score) < $signal->odd_term
            ) {
                $profit += $signal->odd_value * $stake;
            } elseif(
                $signal->event->status == 'finished' && 
                ($signal->event->home_score + $signal->event->away_score) >= $signal->odd_term
            ) {
                //
            }

        $res = $signal->event->status == 'finished' ?
            ($signal->event->home_score + $signal->event->away_score) < $signal->odd_term ? 
                "+ ". ($signal->odd_value * $stake)
            : "- {$stake}"
        : '? ???';

        dump(
            "{$signal->created_at} {$res} [{$signal->id}] ".
            "{$signal->odd_type} ({$signal->odd_term}) {$signal->odd_value}". 
            "🏆 {$signal->event->league->name} ⚽ {$signal->event->name} [{$signal->event->home_score}:{$signal->event->away_score}]"
        );
    });

    dump("Profit: {$profit}₽");
    dump("Bets count: ". ($signals->count()*50));
    dump("Bets count: {$signals->count()}");

    dd(microtime(true) - SPORTDATA_START);