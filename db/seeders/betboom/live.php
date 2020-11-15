<?php
    require __DIR__ . '/../../../src/bootstrap.php';

    use Illuminate\Http\Client\PendingRequest as HttpClient;
    use Illuminate\Support\Str;
    use Illuminate\Support\Carbon;
    use SportData\Models\Sport;
    use SportData\Models\Country;
    use SportData\Models\League;
    use SportData\Models\Event;
    use SportData\Models\OddType;
    use SportData\Models\Odd;

    $client = new HttpClient();

    $sports = $client->post(
        env('BETBOOM_HOST')."/Events/GetSportsWithCount", [
            "partnerId" => env('BETBOOM_PARTNER_ID'),
            "langId" => env('BETBOOM_LANGUAGE_ID'),
            "timeFilter" => 0
        ]
    )->json();

    foreach($sports as $sport) {
        $sportModel = Sport::firstOrCreate(
            ['external_id' => $sport['Id']],
            ['name' => $sport['N']]
        );

        $countres = $client->post(
            env('BETBOOM_HOST')."/Common/GetCountryList", [
                "partnerId" => env('BETBOOM_PARTNER_ID'),
                "langId" => env('BETBOOM_LANGUAGE_ID'),
                "timeFilter" => 0,
                "sportId" => $sport['Id']
            ]
        )->json();

        foreach($countres as $country) {
            $countryModel = Country::firstOrCreate(
                ['external_id' => $country['Id']],
                ['name' => $country['N']]
            );

            $leagues = $client->post(
                env('BETBOOM_HOST')."/Common/GetChampsList", [
                    "partnerId" => env('BETBOOM_PARTNER_ID'),
                    "langId" => env('BETBOOM_LANGUAGE_ID'),
                    "timeFilter" => 0,
                    "countryId" => $country['Id']
                ]
            )->json();

            foreach($leagues as $league) {
                $leagueModel = League::firstOrCreate(
                    ['external_id' => $league['Id']],
                    ['name' => $league['N']]
                );

                $events = $client->post(
                    env('BETBOOM_HOST')."/Events/GetEventsList", [
                        "partnerId" => env('BETBOOM_PARTNER_ID'),
                        "langId" => env('BETBOOM_LANGUAGE_ID'),
                        "timeFilter" => 0,
                        "champId" => $league['Id']
                    ]
                )->json();

                foreach($events as $event) {
                    $eventModel = Event::firstOrCreate(
                        ['external_id' => $event['Id']],
                        [
                            'sport_id' => $sportModel->id,
                            'country_id' => $countryModel->id,
                            'league_id' => $leagueModel->id,
                            'name' => $event['N'],
                            'team1' => $event['HT'],
                            'team2' => $event['AT'],
                            'date' => Carbon::now()->setTimestamp(Str::of($event['D'])->after('/Date(')->before('000+'))->toDateTimeLocalString()
                        ]
                    );

                    foreach($event['StakeTypes'] as $oddType) {
                        $oddTypeModel = OddType::firstOrCreate(
                            ['external_id' => $oddType['Id']],
                            ['name' => $oddType['N']]
                        );
                        
                        foreach($oddType['Stakes'] as $odd) {
                            $oddModel = Odd::firstOrCreate(
                                ['external_id' => $odd['Id']],
                                [
                                    'event_id' => $eventModel->id,
                                    'type_id' => $oddTypeModel->id,
                                    'name' => $odd['N'],
                                    'value' => $odd['F'],
                                    'is_winner' => $odd['IsWinner'],
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
?>