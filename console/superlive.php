<?php
    require __DIR__ . '/../src/bootstrap.php';

    use SportData\Clients\BetBoom\Auth;

    $client = new Auth;
    
    $token = Auth::parseSuperliveToken(
        $client->superlive()
    );

    file_put_contents(
        SPORTDATA_STORAGE_PATH . '/app/betboom/superlive_token.json', 
        '"' . $token . '"'
    );