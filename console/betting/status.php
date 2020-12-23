<?php
    require __DIR__ . '/../../src/bootstrap.php';

    use Illuminate\Support\Carbon;
    use SportData\Clients\BetBoom\Customer;

    $client = new Customer;

    dump(
        (string) Carbon::now(),
        // (string) Carbon::now()->startOfDay(),
        // (string) Carbon::now()->endOfDay(),
    );

    $orders = $client->getOrders(
        (string) Carbon::now()->startOfDay(),
        (string) Carbon::now()->endOfDay()
        // "2020-12-15 00:00:00",
        // "2020-12-15 23:59:59"
    );

    $orders
        ->where('cancel_amount', '>', 0)
        ->each(function($order) use($client) {
            $orderBet = $client->getOrder($order->id);

            dump(
                // $order,
                // $orderBet,
                $orderBet->OB[0]['PS'],
                "{$order->id} ?? {$order->cancel_amount}"
            );
        });

    dump(
        // $orders,
        $orders->sum('bet_amount') .' -> '. $orders->sum('win_amount'),        
        // $orders->first(),
    );

    dd(
        Carbon::now()->format('H:i') .' ('. (microtime(true) - SPORTDATA_START) .')'
    );