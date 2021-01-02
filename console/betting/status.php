<?php
    require __DIR__ . '/../../src/bootstrap.php';

    use Illuminate\Support\Carbon;
    use SportData\Clients\BetBoom\Betting;
    use SportData\Clients\BetBoom\Events;

    $client = new Betting;

    $bets = $client->getBets(
        (string) Carbon::yesterday()->startOfDay(),
        (string) Carbon::now()->endOfDay()
    );

    $bets
        ->where('cancel_amount', '>', 0)
        ->each(function($order) use($client) {
            if(!empty($order->cancel_amount)) {
                $cancelAmount = $client->getBetCancelAmount($order->id);

                dump(
                    '🎫 '. $order->id . ' ' . $cancelAmount->cancel_amount . ' (' . $cancelAmount->cancel_odd . ') [' . $cancelAmount->bet_odd . ']'
                );

                // if(
                //     $cancelAmount->cancel_odd > 1.6
                // ) {
                //     $cancelResponse = $client->cancelBet($order->id, $cancelAmount->cancel_amount);
                //     dump(
                //         '📟 '. $cancelResponse->status
                //     );
                // }
            }
        });

    dump(
        $bets->sum('bet_amount') .' -> '. $bets->sum('win_amount'),
    );

    dd(
        Carbon::now()->format('H:i') .' ('. (microtime(true) - SPORTDATA_START) .')'
    );