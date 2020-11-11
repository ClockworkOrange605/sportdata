<?php 
    require __DIR__ . '/../vendor/autoload.php';

    use Illuminate\Http\Client\PendingRequest;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use Illuminate\Support\Carbon;

    $host = 'https://sport.betboom.ru';

    $sports = (new PendingRequest())->post(
        "{$host}/Events/GetSportsWithCount", [
            "partnerId" => 147,
            "langId" => 2,
            "timeFilter" => 0,
        ]
    )->json();

    foreach ($sports as $sport) {
        dump($sport['Id'] .' '. $sport['N']);

        $countries = (new PendingRequest())->post(
            "{$host}/Common/GetCountryList", [
                "sportId" => $sport['Id'],
                "timeFilter" => 0,
                "langId" => 2,
                "partnerId" => 147,
                //"countryCode" => "RU"
            ]
        )->json();
    
        foreach($countries as $country) {
            dump($country['Id'] .' '. $country['N']);
    
            $champsionships = (new PendingRequest())->post(
                "{$host}/Common/GetChampsList", [
                    "countryId" => $country['Id'],
                    "timeFilter" => 0,
                    "langId" => 1,
                    "partnerId" => 147,
                    "countryCode" => "RU"
                ]
            )->json();
    
            foreach($champsionships as $championship) {
                dump($championship['Id'] .' '. $championship['N']);
    
                $matches = (new PendingRequest())->post(
                    "{$host}/Events/GetEventsList", [
                        "champId" => $championship['Id'],
                        "timeFilter" => 0,
                        "langId" => 1,
                        "partnerId" => 147,
                        "countryCode" => "RU"
                    ]
                )->json();
                
                foreach($matches as $match) {
                    dump($match['Id'] .' '. $match['HT'] .' - '. $match['AT'] .' '. $match['D']);
                    dump($match['Id'] .' '. $match['N'] .' '. Carbon::now()->setTimestamp(Str::of($match['D'])->after('/Date(')->before('000+'))->toRfc850String());
    
                    foreach($match['StakeTypes'] as $stakeType) {
    
                        /* 
                        if($stakeType['Id'] == 1 && abs(
                            $stakeType['Stakes'][0]['F'] -
                            $stakeType['Stakes'][2]['F']
                        ) > 20) {
                            dump($country['Id'] .' '. $country['N']);
                            
                            dump($championship['Id'] .' '. $championship['N']);
    
                            dump($match['Id'] .' '. $match['HT'] .' - '. $match['AT'] .' '. $match['D']);
                            dump($match['Id'] .' '. $match['N']);
    
                            dump($stakeType['Id'] .' '. $stakeType['N'] .' '. abs(
                                $stakeType['Stakes'][0]['F'] -
                                $stakeType['Stakes'][2]['F']
                            ));
    
                            dump(
                                $stakeType['Stakes'][0]['Id'] .' '.
                                $stakeType['Stakes'][0]['N'] .' '.
                                $stakeType['Stakes'][0]['F']
                            );
    
                            dump(
                                $stakeType['Stakes'][1]['Id'] .' '.
                                $stakeType['Stakes'][1]['N'] .' '.
                                $stakeType['Stakes'][1]['F']
                            );
                            dump(
                                $stakeType['Stakes'][2]['Id'] .' '.
                                $stakeType['Stakes'][2]['N'] .' '.
                                $stakeType['Stakes'][2]['F']
                            );
                        }*/
    
                        dump($stakeType['Id'] .' '. $stakeType['N']);
    
                        foreach($stakeType['Stakes'] as $stake) {
                            dump($stake['Id'] .' '. $stake['N'] .' '. $stake['F']);
                        }
                    }
                }
            }
        }
    }
?>