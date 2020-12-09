<?php
    use Illuminate\Support\Arr;
    use SportData\Clients\BetBoom;
    use SportData\Models\Source;
    use SportData\Models\Common\Sport;
    use SportData\Models\Common\Country;
    use SportData\Models\Common\League;
    use SportData\Models\Common\Team;
    use SportData\Models\Events\Event;

    $client = new BetBoom;
    $source = Source::firstOrCreate([
        'name' => 'BetBoom'
    ]);

    /** Sports **/
    foreach($client->getSports() as $sport) {
        $addedSport = Sport::firstOrCreate(
            Arr::only($sport, ['name'])
        );

        $source->sports()->syncWithoutDetaching([
            $addedSport->id => ['external_id' => $sport['source']['source_id']]
        ]);
    }

    /** Countries **/
    $source->sports->each(function($sport) use($source, $client) {
        foreach($client->getCountries($sport->pivot->external_id) as $country) {
            $addedCountry = Country::firstOrCreate(
                array_merge(
                    Arr::only($country, [ 'name' ]),
                    [ 'sport_id' => $sport->id ]
                )   
            );

            $source->countries()->syncWithoutDetaching([
                $addedCountry->id => ['external_id' => $country['source']['source_id']]
            ]);
        }
    });
    
    /** Leagues **/
    $source->countries->each(function($country) use($client, $source) {
        foreach($client->getLeagues($country->pivot->external_id) as $league) {
            $addedLeague = League::firstOrCreate(
                array_merge(
                    Arr::only($league, ['name']),
                    [ 'country_id' => $country->id ]
                )                
            );

            $source->leagues()->syncWithoutDetaching([
                $addedLeague->id => ['external_id' => $league['source']['source_id']]
            ]);
        }
    });

    /** Events **/
    // $source->leagues->each(function($league) use($client, $source) {        
    //     foreach ($client->getEvents(
    //         $league->country->sport->sources->find($source->id)->pivot->external_id,
    //         $league->country->sources->find($source->id)->pivot->external_id,
    //         $league->sources->find($source->id)->pivot->external_id
    //     ) as $result) {
    //         foreach($result['events'] as $event) {
    //             $addedHomeTeam = Team::firstOrCreate([
    //                 'league_id' => $league->id,
    //                 'name' => $event['home_team']['name']
    //             ]);

    //             $addedAwayTeam = Team::firstOrCreate([
    //                 'league_id' => $league->id,
    //                 'name' => $event['away_team']['name']
    //             ]);

    //             $addedEvent = Event::firstOrCreate(
    //                 array_merge(
    //                     [ 'league_id' => $league->id ],
    //                     Arr::only($event, ['name', 'start_at' ])
    //                 ),
    //                 array_merge(
    //                     [
    //                         'home_team_id' => $addedHomeTeam->id, 
    //                         'away_team_id' => $addedAwayTeam->id,
    //                     ],
    //                     Arr::only($event, [
    //                         'status', 'home_score', 'away_score'
    //                     ])
    //                 )
    //             );

    //             // $source->teams()->syncWithoutDetaching($addedHomeTeam);
    //             // $source->teams()->syncWithoutDetaching($addedAwayTeam);
                
    //             $source->events()->syncWithoutDetaching([
    //                 $addedEvent->id => ['external_id' => $event['source']['source_id']]
    //             ]);
    //         }            
    //     }
    // });