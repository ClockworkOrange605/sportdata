<?php 
    namespace SportData\Clients;

    use Illuminate\Http\Client\PendingRequest as HttpClient;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;

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
            $response = $this->client->post('Common/GetSportsListFull');

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

            return $countries;
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

        public function getEvents(int $sportId, int $countryId = 0, int $leagueId = 0) : array
        {
            $response = $this->client->post('Events/GetResults', [
                's' => $sportId,
                'l' => $countryId,
                'g' => $leagueId,
                'sdtSting' => "2018-01-01T00:00:00.000Z",
                'edtSting' => "2020-12-31T23:59:59.999Z",
                'evF' => '',
            ]);

            $events = array_map(function($league) {
                return [
                    'league' => [
                        'name' => $league['N'],
                        'source' => [
                            'source_id' => $league['Id'],
                            'source_type' => self::class,
                        ]
                    ],
                    'events' => self::mapPastEvents($league['E'])
                ];
            }, $response->json());

            return $events;
        }
        
        public function getPrematchEvents(int $leagueId) : array
        {
            $response = $this->client->post('Events/GetEventsList', [
                'champId' => $leagueId
            ]);

            return self::mapEvents($response->json());
        }

        public function getPrematchEvent(int $eventId) : array
        {
            $response = $this->client->post('Events/GetEvent', [
                'eventId' => $eventId
            ]);

            return self::mapEvents($response->json());
        }

        public function getLiveEvents(int $sportId) : array
        {
            $response = $this->client->get('Live/GetEventsList', [
                'sportId' => $sportId
            ]);

            return self::mapEvents($response->json());
        }

        public function getLiveEvent(int $eventId) : array
        {
            $response = $this->client->get('Live/GetEventStakes', [
                'eventNumber' => $eventId
            ]);

            return self::mapEvents($response->json());;
        }

        private static function mapEvents(array $array) : array
        {
            return array_map(function($event) {
                return [
                    'name' => $event['N'],
                    'event_date' => $event['D'],
                    'current_time' => $event['PT'],
                    'period' => trim($event['S']),
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
                            // 'source_id' => $event['??'],
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
                    'odds' => collect(self::mapOdds($event['StakeTypes'])),
                    'source' => [
                        'source_id' => $event['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $array);
        }

        private static function mapPastEvents(array $array) : array
        {
            return array_map(function($event) {
                return [
                    'name' => $event['N'],
                    'status' => Str::of($event['S']) != 'Canceled' ? 'finished' : 'canceled',
                    'start_at' => (string) Carbon::now()->setTimestamp(
                        Str::of($event['D'])->after('/Date(')->before(')/')->before('000+')),
                    'scores' => (string) Str::of($event['S'])->before('<br />'),
                    'home_score' => (int) (string) Str::of($event['S'])
                        ->before('<br />')->before(' ')->before(':'),
                    'away_score' => (int) (string) Str::of($event['S'])
                        ->before('<br />')->before(' ')->after(':'),
                    'home_team' => [
                        'name' => (string) Str::of($event['N'])->before('-')->trim(),
                    ],
                    'away_team' => [
                        'name' => (string) Str::of($event['N'])->after('-')->trim(),
                    ],
                    'result_odds' => self::mapOddValues($event['Stakes']),
                    'source' => [
                        'source_id' => $event['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $array);
        }

        private static function mapOdds(array $array) : array
        {
            return array_map(function($odd_type) {
                return [
                    'name' => $odd_type['N'],
                    'values' => self::mapOddValues($odd_type['Stakes']),
                    'source' => [
                        'source_id' => $odd_type['Id'],
                        'source_type' => self::class
                    ]
                ];
            }, $array);
        }

        private static function mapOddValues(array $array) : array
        {
            return array_map(function($odd_value) {
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
            }, $array);
        }        
    }