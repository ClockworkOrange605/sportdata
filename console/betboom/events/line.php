<?php
    require __DIR__ . '/../../../src/bootstrap.php';

    use SportData\Clients\BetBoom\Events as EventsClient;

    use SportData\Models\Source;
    use SportData\Models\Common\Sport;
    use SportData\Models\Common\Country;
    use SportData\Models\Common\League;
    use SportData\Models\Common\Team;
    use SportData\Models\Events\Event;

    $source = Source::where('name', 'BetBoom')->first();

    $client = new EventsClient('hour');

    $sports = $client->getSportsWithPrematchEvents();
    $sports->each(function($sport) use($source, $client) {
        $addedSport = Sport::findBySourceId($source->id, $sport->id);

        if(empty($addedSport)) {
            $addedSport = Sport::create([
                'name' => $sport->name
            ]);

            $source->sports()->attach([
                $addedSport->id => ['external_id' => $sport->id]
            ]);
        }

        dump(
            $sport->id . ' ' . $sport->name . ' [' . $sport->events_count . ']',
        );

        $countries = $client->getCountriesWithPrematchEvents($sport->id);
        $countries->each(function($country) use($source, $client, $addedSport) {
            $addedCountry = Country::findBySourceId($source->id, $country->id);

            if(empty($addedCountry)) {
                $addedCountry = Country::create([
                    'sport_id' => $addedSport->id,
                    'name' => $country->name
                ]);

                $source->countries()->attach([
                    $addedCountry->id => ['external_id' => $country->id]
                ]);
            }

            dump(
                $country->id . ' ' . $country->name . ' [' . $country->events_count . ']',
            );

            $leagues = $client->getLeaguesWithPrematchEvents($country->id);
            $leagues->each(function($league) use($source, $client, $addedCountry) {
                $addedLeague = League::findBySourceId($source->id, $league->id);

                if(empty($addedLeague)) {
                    $addedLeague = League::create([
                        'country_id' => $addedCountry->id,
                        'name' => $league->name
                    ]);

                    $source->leagues()->attach([
                        $addedLeague->id => ['external_id' => $league->id]
                    ]);
                }

                dump(
                    $league->id . ' ' . $league->name . ' [' . $league->events_count . ']',
                );

                $events = $client->getPrematchEvents($league->id);
                $events->each(function($event) use($source, $client, $addedLeague) {
                    $addedEvent = Event::findBySourceId($source->id, $event->id);

                    $homeTeam = Team::where('league_id', $addedLeague->id)
                        ->where('name', $event->teams->home->name)->first();
                    $awayTeam = Team::where('league_id', $addedLeague->id)
                        ->where('name', $event->teams->away->name)->first();

                    if(empty($addedEvent)) {

                        if(empty($homeTeam)) {
                            $homeTeam = Team::create([
                                'league_id' => $addedLeague->id,
                                'name' => $event->teams->home->name
                            ]);
                        }

                        if(empty($awayTeam)) {
                            $awayTeam = Team::create([
                                'league_id' => $addedLeague->id,
                                'name' => $event->teams->away->name
                            ]);
                        }

                        dump([
                            'league_id' => $addedLeague->id,
                            'home_team_id' => $homeTeam->id,
                            'away_team_id' => $awayTeam->id,
                            'status' => 'prematch',
                            'name' => $event->name,
                            'date' => $event->date                            
                        ]);

                        $addedEvent = Event::create([
                            'league_id' => $addedLeague->id,
                            'home_team_id' => $homeTeam->id,
                            'away_team_id' => $awayTeam->id,
                            'status' => 'prematch',
                            'name' => $event->name,
                            'date' => $event->date,
                        ]);

                        $source->events()->attach([
                            $addedEvent->id => ['external_id' => $event->id]
                        ]);
                    }

                    dump(
                        $event->id . ' ' . $event->livescores_id . ' ' . $event->name,
                    );

                    // $event->odds->each(function($odds) {

                    //     dump(
                    //         $odds->id . ' ' . $odds->name
                    //     );

                    //     $odds->values->each(function($odd) {

                    //         dump(
                    //             $odd->id . ' ' . $odd->name . ' ' . $odd->value . ' ' . $odd->condition
                    //         );

                    //     });
                    // });
                });
            });
        });
    });

    