<?php
    require __DIR__ . '/../src/bootstrap.php';

    use SportData\Models\Betting\Signal;

    $stake = 100; $profit = 0;
    $signals = Signal::with('event')->get();    

    $signals
        // ->where('odd_value', '>', 2)
        ->each(function($signal) use(&$profit, $stake) {
            $profit -= $stake;

            if(
                $signal->event->status == 'finished' && 
                $signal->event->home_score + $signal->event->home_score < $signal->odd_term
            ) {
                $profit += $signal->odd_value * $stake;
            }

        dump(
            $signal->event->status == 'finished' ?
                (
                    $signal->event->home_score + $signal->event->home_score < $signal->odd_term ?
                    (
                        '+ '. (($signal->odd_value * 100) -100)
                    ) :
                    '- 100'
                ) :
                $signal->event->status .' (- 100)'
        );
    });

    dump("Result: {$profit}₽");

    dd(microtime(true) - SPORTDATA_START);