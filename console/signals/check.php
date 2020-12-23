<?php
    require __DIR__ . '/../../src/bootstrap.php';

    use Illuminate\Support\Carbon;
    use SportData\Models\Betting\Signal;

    // $stake = 50; $profit = 4400;
    $stake = 50; $profit = 0;
    $signals = Signal::with('event')
        ->where('created_at', '>', (string) Carbon::now()->startOfDay())

        ->where('odd_value', '>', 4)
        ->where('odd_value', '<', 10)
        ->where('odd_term', '>', 2)

        // ->where('code', 'football_next_goal_odd_9')
        // ->where('code', 'football_next_goal_no_one_10')
        ->orWhere('code', 'football_next_goal_no_one_50')
        // ->orWhere('code', 'football_next_goal_no_one_60')
        // ->orWhere('code', 'football_next_goal_no_one_80')
        // ->orWhere('code', 'football_next_goal_no_one_85')
        // ->orWhere('code', 'football_next_goal_no_one_86')
        // ->orWhere('code', 'football_next_goal_no_one_87')
        // ->orWhere('code', 'football_next_goal_no_one_88')
        // ->orWhere('code', 'football_next_goal_no_one_89')
        
        ->get()
        ->unique('event_id')
        // ->take(20)
        ;

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
                // $profit -= $stake;
            }

        $res = $signal->event->status == 'finished' ?
            ($signal->event->home_score + $signal->event->away_score) < $signal->odd_term ? 
                "+ ". ($signal->odd_value * $stake)
            : "- {$stake}"
        : '? ???';

        // dump(
        //     // $signal->toArray(),
        //     // "{$signal->code} ".
        //     "{$signal->created_at} {$res} [{$signal->id}] ".
        //     "{$signal->odd_type} ({$signal->odd_term}) {$signal->odd_value}". 
        //     "🏆 {$signal->event->league->name} ⚽ {$signal->event->name} [{$signal->event->home_score}:{$signal->event->away_score}]"
        // );
    });

    dump("Current balance: {$profit}₽");
    dump("Bets count: ". ($signals->count()*50));
    dump("Bets count: {$signals->count()}");

    dd(microtime(true) - SPORTDATA_START);