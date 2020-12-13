<?php
    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Models\Common\Sport;
    use SportData\Models\Common\Country;
    use SportData\Models\Common\League;

    $client = new BetBoom;

    use SportData\Models\Source;
    $source = Source::firstOrCreate([
        'name' => 'BetBoom'
    ]);

    foreach($client->getSports() as $sport) {
        $addedSport = Sport::firstOrCreate(['name' => $sport->name]);
        $source->sports()->syncWithoutDetaching([
            $addedSport->id => ['external_id' => $sport->source->id]
        ]);
    }

    $source->sports->each(function($sport) use($source, $client) {
        $countries = $client->getCountriesWithLeagues($sport->pivot->external_id);

        $countries->each(function($country) use($source, $sport) {
            $added_country = Country::firstOrCreate([
                'sport_id' => $sport->id,
                'name' => $country->name,
            ]);
            $source->countries()->syncWithoutDetaching([
                $added_country->id => ['external_id' => $country->source->id]
            ]);

            $country->leagues->each(function($league) use($source, $added_country) {
                $added_league = League::firstOrCreate([
                    'country_id' => $added_country->id,
                    'name' => $league->name,
                ]);
                $source->leagues()->syncWithoutDetaching([
                    $added_league->id => ['external_id' => $league->source->id]
                ]);
            });
        });
    });