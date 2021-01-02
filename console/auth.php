<?php
    require __DIR__ . '/../src/bootstrap.php';

    use SportData\Clients\BetBoom\Auth;

    $client = new Auth;

    $client->home(
        Auth::parseToken(
            $client->index()
        )
    );

    dump(
        $client->authorize()
    );

    sleep(10);

    $client->home(
        Auth::parseToken(
            $client->index()
        )
    );