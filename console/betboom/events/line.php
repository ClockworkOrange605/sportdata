<?php
    require __DIR__ . '/../../../src/bootstrap.php';

    use SportData\Clients\BetBoom\Events as EventsClient;

    use SportData\Models\Source;
    use SportData\Models\Common\Sport;
    use SportData\Models\Common\Country;
    use SportData\Models\Common\League;
    use SportData\Models\Common\Team;
    use SportData\Models\Common\Odd;
    use SportData\Models\Events\Event;
    use SportData\Models\Events\EventOdd;

    $source = Source::where('name', 'BetBoom')->first();

    $client = new EventsClient('day');

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

                        $event->odds->each(function($odds) use($source, $client, $addedEvent) {
                            $addedOdd = Odd::findBySourceId($source->id, $odds->id);

                            if(empty($eventOdd)) {
                                $addedOdd = Odd::create(['name' => $odds->name]);

                                $source->odds()->attach([
                                    $addedOdd->id => ['external_id' => $odds->id]
                                ]);
                            }
    
                            $odds->values->each(function($odd) use($source, $addedEvent, $addedOdd) {
                                $eventOdd = EventOdd::findBySourceId($source->id, $odd->id);

                                if(empty($eventOdd)) {
                                    $eventOdd = EventOdd::create([
                                        'type_id' => $addedOdd->id,
                                        'event_id' => $addedEvent->id,
                                        'type' => 'prematch',
                                        'name' => $odd->name,
                                        'value' => $odd->value,
                                        'condition' => $odd->condition
                                    ]);

                                    $source->event_odds()->attach([
                                        $eventOdd->id => ['external_id' => $odd->id]
                                    ]);
                                }    
                            });
                        });           
                    }
                });
            });
        });
    });
    