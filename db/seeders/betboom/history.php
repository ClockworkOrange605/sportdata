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

    $dateFrom = Carbon::createFromDate(2020, 1, 1)
                    ->startOfDay()
                    ->toIso8601ZuluString();

    $dateUntil = Carbon::createFromDate(2022, 1, 1)
                    ->startOfDay()
                    ->toIso8601ZuluString();

    $sports = $client->post(
        env('BETBOOM_HOST')."/Common/GetSportsListFull", [
            "partnerId" => env('BETBOOM_PARTNER_ID'),
            "langId" => env('BETBOOM_LANGUAGE_ID'),
        ]
    )->json();

    foreach($sports as $sport) {
        $sportModel = Sport::firstOrCreate(
            ['external_id' => $sport['Id']],
            ['name' => $sport['N']]
        );

        $countryLeagues = $client->post(
            env('BETBOOM_HOST')."/Events/GetResChampsList", [
                "partnerId" => env('BETBOOM_PARTNER_ID'),
                "langId" => env('BETBOOM_LANGUAGE_ID'),
                "sp" => $sportModel->external_id,
                "st" => $dateFrom,
                "en" => $dateUntil,
            ]
        )->json();

        foreach($countryLeagues as $countryLeague) {
            $countryExternalId = (string) Str::of($countryLeague['K'])->after('l')->before('_g');
            $leagueExternalId = (string) Str::of($countryLeague['K'])->after('_g');
            $leagueExternalName = (string) Str::of($countryLeague['V'])->trim('&nbsp;');

            if($leagueExternalId) continue;
            
            $countryModel = Country::firstOrCreate(
                ['external_id' => $countryExternalId],
                ['name' => $leagueExternalName]
            );

            $events = $client->post(
                env('BETBOOM_HOST')."/Events/GetResults", [
                    "partnerId" => env('BETBOOM_PARTNER_ID'),
                    "langId" => env('BETBOOM_LANGUAGE_ID'),
                    "s" => $sportModel->external_id,
                    "l" => $countryModel->external_id, 
                    "g" => 0,
                    "sdtSting" => $dateFrom,
                    "edtSting" => $dateUntil,
                    "evF" => ""
                ]
            )->json();

            foreach($events as $eventGroup) {
                $leagueModel = League::firstOrCreate(
                    ['external_id' => $eventGroup['Id']],
                    [
                        'country_id' => $countryModel->id,
                        'name' => $eventGroup['N']                 
                    ]
                );

                foreach($eventGroup['E'] as $event) {
                    $eventModel = Event::firstOrCreate(
                        ['external_id' => $event['Id']],
                        [
                            'sport_id' => $sportModel->id,
                            'country_id' => $countryModel->id,
                            'league_id' => $leagueModel->id,
                            'name' => $event['N'],
                            'score' => (string) Str::of($event['S'])->before(' '),
                            'date' => Carbon::now()->setTimestamp(
                                        Str::of($event['D'])->after('/Date(')->before('000+')
                                    )->toDateTimeLocalString(),
                            'home_team' => (string) Str::of($event['N'])->before(' - '),
                            'away_team' => (string) Str::of($event['N'])->after(' - '),
                            'home_score' => (int)(string) Str::of($event['S'])
                                                ->before(' ')->before(':'),
                            'away_score' => (int)(string) Str::of($event['S'])
                                                ->before(' ')->after(':')
                        ]
                    );

                    foreach($event['Stakes'] as $odd) {
                        $oddTypeModel = OddType::firstOrCreate(
                            ['external_id' => $odd['GId']],
                            ['name' => $odd['GN']]
                        );

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
?>