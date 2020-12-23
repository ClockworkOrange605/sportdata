<?php
    require __DIR__ . '/../src/bootstrap.php';

    use Illuminate\Support\Str;
    use SportData\Clients\BetBoom\Auth;


    $client = new Auth;

    
    $token = Str::of($client->index())
            ->after("server: 'https://sport.betboom.ru/',")
            ->before("login: 'sport.showRegisterNotComplete'")
            ->trim()
            ->after("token: '")
            ->before("',");
    $client->home($token);

    dump(        
        $client->authorize(),
    );
    $client = new Auth;
    sleep(10);

    
    $token = Str::of($client->index())
            ->after("server: 'https://sport.betboom.ru/',")
            ->before("login: 'sport.showRegisterNotComplete'")
            ->trim()
        ->after("token: '")
        ->before("',");    
    $client->home($token);
    
    dump(
        $client->setMagic(),
        sleep(10),
        $client->getCoupon(),
        sleep(10),
        $client->getOrders(
            "2020-12-15 00:00:00",
            "2020-12-15 23:59:59"
        )
    );
    
    

    // preg_match('/(token)/', $client->index(), $matches);

    // dump($matches);