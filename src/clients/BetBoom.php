<?php 
    namespace SportData\Clients;

    use Illuminate\Http\Client\PendingRequest as HttpClient;

    class BetBoom
    {
        private $client;

        public function __construct()
        {
            $this->client = new HttpClient();
            $this->client->baseUrl(env('BETBOOM_HOST'));
            $this->client->withOptions([
                'json' => [
                    'partnerId' => env('BETBOOM_PARTNER_ID'),
                    'langId' => env('BETBOOM_LANGUAGE_ID'),
                    'timeFilter' => 0,
                ]
            ]);
        }

        public function getSports() : array
        {
            $response = $this->client->post('Events/GetSportsWithCount');

            $sports = array_map(function($sport) {
                return [
                    'name' => $sport['N'],
                    'source' => [
                        'source_id' => $sport['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $response->json());

            return $sports;
        }

        public function getCountries(int $sportId) : array
        {
            $response = $this->client->post('Common/GetCountryList', [
                'sportId' => $sportId,
            ]);

            $countries = array_map(function($country) { 
                return [
                    'name' => $country['N'],
                    'source' => [
                        'source_id' => $country['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $response->json());

            return($countries);
        }

        public function getLeagues(int $countryId) : array
        {
            $response = $this->client->post('Common/GetChampsList', [
                'countryId' => $countryId,
            ]);

            $leagues = array_map(function($league) {
                return [
                    'name' => $league['N'],
                    'source' => [
                        'source_id' => $league['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $response->json());

            return $leagues;
        }
        
        public function getEvents(int $leagueId) : array
        {
            $request = $this->client->post('Events/GetEventsList', [
                'champId' => $leagueId
            ]);

            $events = array_map(function($event) {
                return [
                    'event_date' => $event['D'],
                    'sport' => [
                        'name' => $event['SN'],
                        'source' => [
                            'source_id' => $event['SId'],
                            'source_type' => self::class
                        ]
                    ],
                    'country' => [
                        'name' => $event['CtN'],
                        'source' => [
                            'source_id' => $event['CId'],
                            'source_type'=> self::class
                        ]
                    ],
                    'league' => [
                        'name' => $event['CN'], 
                        'source' => [
                            'source_id' => $event['CId'],
                            'source_type' => self::class
                        ]
                    ],
                    'home_team' => [
                        'name' => $event['HT'],
                    ],
                    'away_team' => [
                        'name' => $event['AT'],
                    ],
                    'odds' => array_map(function($odd_type) {
                        return [
                            'name' => $odd_type['N'],
                            'values' => array_map(function($odd_value) {
                                return [ 
                                    'name' => $odd_value['N'],
                                    'term' => $odd_value['A'],
                                    'value' => $odd_value['F'],
                                    'is_winner' => $odd_value['IsWinner'],
                                    'source' => [
                                        'source_id' => $odd_value['Id'],
                                        'source_type' => self::class
                                    ]
                                ];
                            }, $odd_type['Stakes']),
                            'source' => [
                                'source_id' => $odd_type['Id'],
                                'source_type' => self::class
                            ]
                        ];
                    }, $event['StakeTypes']),
                    'source' => [
                        'source_id' => $event['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $request->json());

            return $events;
        }

        public function getEvent(int $eventId) : array
        {
            $response = $this->client->post('Events/GetEvent', [
                'eventId' => $eventId
            ]);

            $odds = array_map(function($event) {
                return [
                    'event_date' => $event['D'],
                    'sport' => [
                        'name' => $event['SN'],
                        'source' => [
                            'source_id' => $event['SId'],
                            'source_type' => self::class
                        ]
                    ],
                    'country' => [
                        'name' => $event['CtN'],
                        'source' => [
                            'source_id' => $event['CId'],
                            'source_type'=> self::class
                        ]
                    ],
                    'league' => [
                        'name' => $event['CN'], 
                        'source' => [
                            'source_id' => $event['CId'],
                            'source_type' => self::class
                        ]
                    ],
                    'home_team' => [
                        'name' => $event['HT'],
                    ],
                    'away_team' => [
                        'name' => $event['AT'],
                    ],
                    'odds' => array_map(function($odd_type) {
                        return [
                            'name' => $odd_type['N'],
                            'values' => array_map(function($odd_value) {
                                return [ 
                                    'name' => $odd_value['N'],
                                    'term' => $odd_value['A'],
                                    'value' => $odd_value['F'],
                                    'is_winner' => $odd_value['IsWinner'],
                                    'source' => [
                                        'source_id' => $odd_value['Id'],
                                        'source_type' => self::class
                                    ]
                                ];
                            }, $odd_type['Stakes']),
                            'source' => [
                                'source_id' => $odd_type['Id'],
                                'source_type' => self::class
                            ]
                        ];
                    }, $event['StakeTypes']),
                    'source' => [
                        'source_id' => $event['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $response->json());

            return $odds;
        }

        public function getLiveEvents(int $sportId) : array
        {
            $response = $this->client->get('Live/GetEventsList', [
                'sportId' => $sportId
            ]);

            $events = array_map(function($event) {
                return [
                    'event_date' => $event['D'],
                    'current_time' => $event['PT'],
                    'period' => $event['S'],
                    'scores' => $event['SS'],
                    'home_score' => $event['HS'],
                    'away_score' => $event['AS'],
                    'sport' => [
                        'name' => $event['SN'],
                        'source' => [
                            'source_id' => $event['SId'],
                            'source_type' => self::class
                        ]
                    ],
                    'country' => [
                        'name' => $event['CtN'],
                        'source' => [
                            'source_id' => $event['CId'],
                            'source_type'=> self::class
                        ]
                    ],
                    'league' => [
                        'name' => $event['CN'], 
                        'source' => [
                            'source_id' => $event['CId'],
                            'source_type' => self::class
                        ]
                    ],
                    'home_team' => [
                        'name' => $event['HT'],
                    ],
                    'away_team' => [
                        'name' => $event['AT'],
                    ],
                    'odds' => array_map(function($odd_type) {
                        return [
                            'name' => $odd_type['N'],
                            'values' => array_map(function($odd_value) {
                                return [ 
                                    'name' => $odd_value['N'],
                                    'term' => $odd_value['A'],
                                    'value' => $odd_value['F'],
                                    'is_winner' => $odd_value['IsWinner'],
                                    'source' => [
                                        'source_id' => $odd_value['Id'],
                                        'source_type' => self::class
                                    ]
                                ];
                            }, $odd_type['Stakes']),
                            'source' => [
                                'source_id' => $odd_type['Id'],
                                'source_type' => self::class
                            ]
                        ];
                    }, $event['StakeTypes']),
                    'source' => [
                        'source_id' => $event['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $response->json());

            return $events;
        }

        public function getLiveEvent(int $eventId) : array
        {
            $response = $this->client->get('Live/GetEventStakes', [
                'eventNumber' => $eventId
            ]);

            $event = array_map(function($event) {
                return [
                    'event_date' => $event['D'],
                    'current_time' => $event['PT'],
                    'period' => $event['S'],
                    'scores' => $event['SS'],
                    'home_score' => $event['HS'],
                    'away_score' => $event['AS'],
                    'sport' => [
                        'name' => $event['SN'],
                        'source' => [
                            'source_id' => $event['SId'],
                            'source_type' => self::class
                        ]
                    ],
                    'country' => [
                        'name' => $event['CtN'],
                        'source' => [
                            'source_id' => $event['CId'],
                            'source_type'=> self::class
                        ]
                    ],
                    'league' => [
                        'name' => $event['CN'], 
                        'source' => [
                            'source_id' => $event['CId'],
                            'source_type' => self::class
                        ]
                    ],
                    'home_team' => [
                        'name' => $event['HT'],
                    ],
                    'away_team' => [
                        'name' => $event['AT'],
                    ],
                    'odds' => array_map(function($odd_type) {
                        return [
                            'name' => $odd_type['N'],
                            'values' => array_map(function($odd_value) {
                                return [ 
                                    'name' => $odd_value['N'],
                                    'term' => $odd_value['A'],
                                    'value' => $odd_value['F'],
                                    'is_winner' => $odd_value['IsWinner'],
                                    'source' => [
                                        'source_id' => $odd_value['Id'],
                                        'source_type' => self::class
                                    ]
                                ];
                            }, $odd_type['Stakes']),
                            'source' => [
                                'source_id' => $odd_type['Id'],
                                'source_type' => self::class
                            ]
                        ];
                    }, $event['StakeTypes']),
                    'source' => [
                        'source_id' => $event['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $response->json());

            return $event;
        }

        public function getResults(int $sportId, int $countryId = 0, int $leagueId = 0) : array
        {
            $response = $this->client->post('Events/GetResults', [
                's' => $sportId,
                'l' => $countryId,
                'g' => $leagueId,
                'sdtSting' => "2020-11-27T21:00:00.000Z",
                'edtSting' => "2020-11-30T21:00:00.000Z",
                'evF' => '',
            ]);

            dd($response->json());

            return [];
        }
        
    }