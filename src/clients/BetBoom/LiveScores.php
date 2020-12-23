<?php
    namespace SportData\Clients\BetBoom;

    use Illuminate\Http\Client\PendingRequest as HttpClient;

    class LiveScores {
        private $client;
        private $translation;

        public function __construct() {
            $this->client = new HttpClient;
            $this->client->baseUrl(env('BETBOOM_LIVESCORES_HOST'));
            $this->client->withOptions([
                'query' => [
                    'lg' => env('BETBOOM_LIVESCORES_LANGUAGE'),
                ]
            ]);

            $this->translation = json_decode('{"status":{"-":"Не Начался","1T":"Первый Тайм","2T":"Второй Тайм","HT":"Перерыв","FT":"Завершён","Fin":"Завершён","Final":"Завершён","Res":"Завершён","ET":"Доп. Время","AET":"Завершён ДВ","Canc":"Отменён","Pen":"Завершён Пен","Pst":"Отложен","Ssp":"Остановлен","Susp":"Остановлен","AW":"Awarded Win","NIY":"Данных Нет","1S":"Сет 1","2S":"Сет 2","3S":"Сет 3","4S":"Сет 4","5S":"Сет 5","Ret":"Отказ игрока","1Q":"Четверть 1","2Q":"Четверть 2","3Q":"Четверть 3","4Q":"Четверть 4","E1Q":"Завершён 1Ч","E2Q":"Перерыв","E3Q":"Завершён 3Ч","E4Q":"Завершён 4Ч","OT":"Овертайм","AOT":"Завершён OT","1P":"Период 1","2P":"Период 2","3P":"Период 3","E1P":"Завершён 1П","E2P":"Завершён 2П","E3P":"Завершён 3П","Bulit":"Буллит","11M":"11-метровые","ToFin":"Будет Доигран","IN1":"1ый Иннинг","IN2":"2ой Иннинг","IN3":"3ий Иннинг","IN4":"4ый Иннинг","IN5":"5ый Иннинг","IN6":"6ой Иннинг","IN7":"7ой Иннинг","IN8":"8ой Иннинг","IN9":"9ый Иннинг","IN10":"10ый Иннинг","IN11":"11ый Иннинг","IN12":"12ый Иннинг","IN13":"13ый Иннинг","IN14":"14ый Иннинг","IN15":"15ый Иннинг","IN16":"16ый Иннинг","IN17":"17ый Иннинг","IN18":"18ый Иннинг","IN19":"19ый Иннинг","IN20":"20ый Иннинг","F10":"После 10ИН","F11":"После 11ИН","F12":"После 12ИН","F13":"После 13ИН","F14":"После 14ИН","F15":"После 15ИН","F16":"После 16ИН","F18":"После 17ИН","F19":"После 19ИН","F20":"После 20ИН","E1":"Завершён ИН1","E2":"Завершён ИН2","E3":"Завершён ИН3","E4":"Завершён ИН4","E5":"Завершён ИН5","E6":"Завершён ИН6","E7":"Завершён ИН7","E8":"Завершён ИН8","E9":"Завершён ИН9","E10":"Завершён ИН10","E11":"Завершён ИН11","E12":"Завершён ИН12","E13":"Завершён ИН13","E14":"Завершён ИН14","E15":"Завершён ИН15","E16":"Завершён ИН16","E17":"Завершён ИН17","E18":"Завершён ИН18","E19":"Завершён ИН19","E20":"Завершён ИН20","M1":"Перерыв ИН1","M2":"Перерыв ИН2","M3":"Перерыв ИН3","M4":"Перерыв ИН4","M5":"Перерыв ИН5","M6":"Перерыв ИН6","M7":"Перерыв ИН7","M8":"Перерыв ИН8","M9":"Перерыв ИН9","M10":"Перерыв ИН10","M11":"Перерыв ИН11","M12":"Перерыв ИН12","M13":"Перерыв ИН13","M14":"Перерыв ИН14","M15":"Перерыв ИН15","M16":"Перерыв ИН16","M17":"Перерыв ИН17","M18":"Перерыв ИН18","M19":"Перерыв ИН19","M20":"Перерыв ИН20","1G":"Первая Игра","2G":"Вторая Игра","3G":"Третья Игра","4G":"Четвертая Игра","5G":"Пятая Игра","Live":"Live"},"sportName":{"1":"Футбол","2":"Хоккей","3":"Баскетбол","4":"Теннис","5":"Формула 1","6":"Бейсбол","7":"Ам. Футбол","8":"Скачки","9":"Гольф","10":"Гандбол","11":"Волейбол","12":"Регби Союз","14":"Крикет","17":"Регби Лига","18":"Футзал","19":"Пляжный Футбол","20":"Водное поло","21":"Киберспорт","22":"Настольный Футбол"},"terms":{"referee":"Судья","venue":"Стадион","preview":"Превью","missing players":"Отсутствующие","probable lineups":"Вероятные составы","match facts":"Факты","news":"Новости","inj":"Травма","ssp":"Дискв.","summary":"Суммарный","lineups":"Составы","stats":"Статистика","statistics":"Статистика","h2h":"H2H","standings":"Таблица","drawsheet":"Турнирная Сетка","odds":"Коэффициенты","match info":"Информация о матче","live":"Лайв","scheduled":"Расписание","favorites":"Избранные","favorite games":"Избранные Игры","starting lineups":"Составы","substitutes":"Запасные","formation":"Тактика","type your search here":"Поиск","search":"Поиск","all games":"Все Игры","live games":"Лайв Игры","finished games":"Завершенные Игры","upcoming games":"Расписание","calendar":"Календарь","upcoming":"Расписание","other competitions [a-z]":"Все Соревнования [А-Я]","live score":"Результаты on-line","latest scores":"Последние Результаты","score":"Результат","no games":"Игр нет","no games for selected date":"Нет игр для выбранной даты","connecting":"Подключение","today":"Сегодня","failed to load":"Ошибка при загрузке","last matches":"Последние Игры","head-to-head matches":"Очные встречи","head to head":"Head To Head","points":"Очков","mp":"И","g":"М","p":"О","w":"В","d":"Н","l":"П","team":"Команда","yellow cards":"Желтые Карточки","red cards":"Красные Карточки","main events":"Главные События","substitutions":"Замены","countries":"Страны","top leagues":"Топ Лиги","today\'s matches":"Сегодняшние Матчи","overall":"Общее","home":"Дома","away":"На выезде","settings":"Настройки","language":"Язык","cancel":"Отмена","save":"Сохранить","overview":"Обзор","tables":"Таблицы","teams":"Команды","comparison":"Сравнение","league record":"Цифры","under":"Меньше","over":"Больше","biggest home victory":"Крупнейшая Домашняя Победа","biggest away victory":"Крупнейшая Гостевая Победа","squad":"Состав","age":"Возраст","results":"Результаты","fixtures":"График","nationality":"Национальность","position":"Позиция","height":"Рост","weight":"Вес","most goals":"Больше всех голов","one team":"Одна Команда","both teams":"Обе Команды","matches played":"Матчей сыграно","home wins":"Домашних побед","away wins":"Гостевых побед","draws":"Ничьей","scoring draws":"Голевых ничьей","no score draws":"Нулевых ничьей","per match":"За матч","nav:home":"Главная","results/fixtures":"Игры","total record":"Тотал","home record":"Дома","away record":"В Гостях","1 h record":"Первый тайм","2 h record":"Второй тайм","clean sheet":"Сухая Серия","fail to score":"Не Забил","score first":"Забил Первым","opp. scores first":"Пр. Забил Первым","1 half win / match win (w/w)":"1Т победа / победа в матче","1 half draw / match win (d/w)":"1Т ничья / победа в матче","1 half lose / match win (l/w)":"1Т поражение / победа в матче","1 half win / match draw (w/d)":"1Т победа / ничья в матче","1 half draw / match draw (d/d)":"1Т ничья / ничья в матче","1 half lose / match draw (l/d)":"1Т поражение / ничья в матче","1 half win / match lose (w/l)":"1Т победа / поражение в матче","1 half draw / match lose (d/l)":"1Т ничья / поражение в матче","1 half lose/ match lose (l/l)":"1Т поражение / поражение в матче","half time/full time record":"Первый тайм / Матч","total":"Тотал","driver":"Пилот","time":"Время","total laps":"Всего Кругов","current lap":"Текущий Круг","date":"Дата","distance":"Расстояние","name":"Имя","total yards":"Всего ярдов","start":"Старт","hole":"Лунка","par":"Пар","win":"Победа","runners":"Лошадей","csf":"CSF","df":"DF","winning jockey":"Выигравший жокей","winning trainer":"Выигравший тренер","runner":"Лошадь","video":"Видео"},"playerPosition":{"g":"Вратарь","d":"Защитник","m":"Полузащитник","f":"Форвард"},"statName":{"ball possession (%)":"Владение Мячом (%)","shots":"Удары","shots on goal":"Удары в Створ","fouls":"Нарушения","corner kicks":"Угловые","offside":"Офсайд","goals":"Голов","saves":"Сейвы","cautions":"Предупреждения","expulsions":"Удаления","total passes":"Всего Передач","completed passes":"Точные Передачи","aces":"Эйсы","double faults":"Двойные Ошибки","break points saved":"Отбитые Брейкпойнты","1st serve %":"1ая Подача %","1st serve points won":"Очки с 1ых Подач","2nd serve points won":"Очки сo 2ых Подач","service games played":"Service Games Played"},"weekDay":["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"],"betStake":{"w1":"П1","d":"X","w2":"П2"}}');
            $this->translation = json_decode('{"status":{"-":"Scheduled","1T":"First Half","2T":"Second Half","HT":"Half Time","FT":"Finished","Fin":"Finished","Final":"Finished","Res":"Finished","ET":"Extra Time","AET":"After ET","Canc":"Canceled","Pen":"After Pen","Pst":"Postponed","Ssp":"Suspended","Susp":"Suspended","AW":"Awarded Win","NIY":"No Info Yet","1S":"Set 1","2S":"Set 2","3S":"Set 3","4S":"Set 4","5S":"Set 5","Ret":"Retired","1Q":"Quarter 1","2Q":"Quarter 2","3Q":"Quarter 3","4Q":"Quarter 4","E1Q":"End 1Q","E2Q":"Half Time","E3Q":"End 3Q","E4Q":"End 4Q","OT":"Overtime","AOT":"After OT","1P":"Period 1","2P":"Period 2","3P":"Period 3","E1P":"End 1P","E2P":"End 2P","E3P":"End 3P","Bulit":"Bulit","11M":"Penalties","ToFin":"To Be Finished","IN1":"1st Inning","IN2":"2nd Inning","IN3":"3rd Inning","IN4":"4th Inning","IN5":"5th Inning","IN6":"6th Inning","IN7":"7th Inning","IN8":"8th Inning","IN9":"9th Inning","IN10":"10th Inning","IN11":"11th Inning","IN12":"12th Inning","IN13":"13th Inning","IN14":"14th Inning","IN15":"15th Inning","IN16":"16th Inning","IN17":"17th Inning","IN18":"18th Inning","IN19":"19th Inning","IN20":"20th Inning","F10":"After 10IN","F11":"After 11IN","F12":"After 12IN","F13":"After 13IN","F14":"After 14IN","F15":"After 15IN","F16":"After 16IN","F17":"After 17IN","F18":"After 18IN","F19":"After 19IN","F20":"After 20IN","E1":"Finished IN1","E2":"Finished IN2","E3":"Finished IN3","E4":"Finished IN4","E5":"Finished IN5","E6":"Finished IN6","E7":"Finished IN7","E8":"Finished IN8","E9":"Finished IN9","E10":"Finished IN10","E11":"Finished IN11","E12":"Finished IN12","E13":"Finished IN13","E14":"Finished IN14","E15":"Finished IN15","E16":"Finished IN16","E17":"Finished IN17","E18":"Finished IN18","E19":"Finished IN19","E20":"Finished IN20","M1":"Middle IN1","M2":"Middle IN2","M3":"Middle IN3","M4":"Middle IN4","M5":"Middle IN5","M6":"Middle IN6","M7":"Middle IN7","M8":"Middle IN8","M9":"Middle IN9","M10":"Middle IN10","M11":"Middle IN11","M12":"Middle IN12","M13":"Middle IN13","M14":"Middle IN14","M15":"Middle IN15","M16":"Middle IN16","M17":"Middle IN17","M18":"Middle IN18","M19":"Middle IN19","M20":"Middle IN20","1G":"1st Game","2G":"2nd Game","3G":"3rd Game","4G":"4th Game","5G":"5th Game","Live":"Live"},"sportName":{"1":"Football","2":"Hockey","3":"Basketball","4":"Tennis","5":"Formula 1","6":"Baseball","7":"Am. Football","8":"Horse Racing","9":"Golf","10":"Handball","11":"Volleyball","12":"Rugby Union","14":"Cricket","17":"Rugby League","18":"Futsal","19":"Beach Football","20":"Waterpolo","21":"E-Sports","22":"Table Football"},"terms":{"referee":"Referee","venue":"Venue","preview":"Preview","missing players":"Missing Players","probable lineups":"Probable Lineups","match facts":"Match Facts","news":"News","inj":"Injury","ssp":"Suspension","summary":"Summary","lineups":"Lineups","stats":"Stats","statistics":"Statistics","h2h":"H2H","standings":"Standings","drawsheet":"Draw","odds":"Odds","match info":"Match Info","live":"Live","scheduled":"Scheduled","favorites":"Favorites","favorite games":"Favorite Games","starting lineups":"Starting Lineups","substitutes":"Substitutes","formation":"Formation","type your search here":"Type your search here","search":"Search","all games":"All Games","live games":"Live Games","finished games":"Finished Games","upcoming games":"Upcoming Games","calendar":"Calendar","upcoming":"Upcoming","other competitions [a-z]":"Other Competitions [A-Z]","live score":"Live Score","latest scores":"Latest Scores","score":"Score","no games":"No Games","no games for selected date":"No Games For Selected Date","connecting":"Connecting","today":"Today","failed to load":"Failed to Load","last matches":"Last Matches","head-to-head matches":"Head-To-Head Matches","head to head":"Head To Head","points":"Points","mp":"MP","g":"G","p":"P","w":"W","d":"D","l":"L","team":"Team","yellow cards":"Yellow Cards","red cards":"Red Cards","main events":"Main Events","substitutions":"Substitutions","top leagues":"Top Leagues","countries":"Countries","today\'s matches":"Today\'s Matches","overall":"Overall","home":"Home","away":"Away","settings":"Settings","language":"Language","cancel":"Cancel","save":"Save","overview":"Overview","tables":"Tables","teams":"Teams","comparison":"Comparison","league record":"League Record","under":"Under","over":"Over","biggest home victory":"Biggest Home Victory","biggest away victory":"Biggest Away Victory","squad":"Squad","age":"Age","results":"Results","fixtures":"Fixtures","nationality":"Nationality","position":"Position","height":"Height","weight":"Weight","most goals":"Most Goals","one team":"One Team","both teams":"Both Teams","matches played":"Matches Played","home wins":"Home Wins","away wins":"Away Wins","draws":"Draws","scoring draws":"Scoring Draws","no score draws":"No Score Draws","per match":"Per Match","nav:home":"Home","results/fixtures":"Results/Fixtures","total record":"Total Record","home record":"Home Record","away record":"Away Record","1 h record":"1 H Record","2 h record":"2 H Record","clean sheet":"Clean Sheet","fail to score":"Fail To Score","score first":"Score First","opp. scores first":"Opp. scores first","1 half win / match win (w/w)":"1 Half Win / Match Win (W/W)","1 half draw / match win (d/w)":"1 Half Draw / Match Win (D/W)","1 half lose / match win (l/w)":"1 Half Lose / Match Win (L/W)","1 half win / match draw (w/d)":"1 Half Win / Match Draw (W/D)","1 half draw / match draw (d/d)":"1 Half Draw / Match Draw (D/D)","1 half lose / match draw (l/d)":"1 Half Lose / Match Draw (L/D)","1 half win / match lose (w/l)":"1 Half Win / Match Lose (W/L)","1 half draw / match lose (d/l)":"1 Half Draw / Match Lose (D/L)","1 half lose/ match lose (l/l)":"1 Half Lose / Match Lose (L/L)","half time/full time record":"Half Time/Full Time Record","total":"Total","driver":"Driver","time":"Time","total laps":"Total Laps","current lap":"Current Lap","date":"Date","distance":"Distance","name":"Name","total yards":"Total Yards","start":"Start","hole":"Hole","par":"Par","win":"Win","runners":"Runners","csf":"CSF","df":"DF","winning jockey":"Winning Jockey","winning trainer":"Winning Trainer","runner":"Runner","video":"Video"},"statName":{"ball possession (%)":"Ball Possession (%)","shots":"Shots","shots on goal":"Shots on Goal","fouls":"Fouls","corner kicks":"Corner Kicks","offside":"Offside","goals":"Goals","saves":"Saves","cautions":"Cautions","expulsions":"Expulsions","total passes":"Total Passes","completed passes":"Completed Passes","aces":"Aces","double faults":"Double Faults","break points saved":"Break Points Saved","1st serve %":"1st Serve %","1st serve points won":"1st Serve Points Won","2nd serve points won":"2nd Serve Points Won","service games played":"Service Games Played"},"weekDay":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"betStake":{"w1":"W1","d":"X","w2":"W2"},"playerPosition":{"g":"Goalkeeper","d":"Defender","m":"Midfielder","f":"Forward"}}');

            // dd($this->translation);
            // dd((array) $this->translation->status);
            // dd(((array) $this->translation->status)['-']);
        }

        ///////////////////////////////////////////////////////////////////////////////////////////

        public function getFeed(int $sportId, string $date = 'today') {
            $response = $this->client->get("feed/{$sportId}/{$date}/");

            return (object) [
                'competitions' => collect(self::mapCompetitions($response->json('response')['comp'])),
                'games' => collect(self::mapEvents($response->json('response')['game'])),
            ];
        }

        public function getFeedPlus(int $sportId, string $date = 'today') {
            $response = $this->client->get("feed/{$sportId}/plus/{$date}/");

            return (object) [
                'competitions' => collect(self::mapCompetitions($response->json('response')['comp'])),
                'games' => collect(self::mapEvents($response->json('response')['game'])),
            ];
        }

        public function getRecent(int $sportId) {
            $response = $this->client->get("/feed/{$sportId}/recent/");

            return (object) [
                'games' => collect(self::mapEventsShort($response->json('response')['game'])),
            ];
        }

        public function getCompetitions(int $sportId) {
            $response = $this->client->get("/competition/{$sportId}/");

            dd($response->json());

            return (object) [
                // `'competitions'
            ];
        }

        public function getCompetition(int $sportId, int $competitionId) {
            $response = $this->client->get("/competition/{$sportId}/{$competitionId}/");

            dd($response->json());

            return (object) [
                // 'comp'
            ];
        }

        public function getStandings(int $sportId, int $competitionId) {
            $response = $this->client->get("standings/{$sportId}/{$competitionId}/");

            dd($response->json());

            // $response->json()

            return (object) [
                // ''
            ];
        }

        public function getEvent(int $sportId, int $eventId) {
            $response = $this->client->get("/game/{$sportId}/{$eventId}/");

            return (object) self::mapEvent($response->json('response'));
        }

        ////////////////////////////////////////////////////////////////////////////////////////////

        private function mapCompetitions($array) {
            return array_map(function($competition) {
                return (object) [
                    'id' => $competition['id'],
                    'name' => $competition['name'],
                    'country' => (object) [
                        'id' => $competition['countryId'],
                        'name' => $competition['countryName']
                    ],
                    'original' => (object) $competition,
                ];
            }, $array);
        }

        private function mapEvents($array) {
            return array_map(function($game) { 
                return (object) [
                    'id' => $game['id'],

                    'status' => $game['status'],
                    'status_live' => $game['status'],

                    'started_at' => $game['dateT'] / 1000,
                    'updated_at' => $game['chT'] / 1000,

                    'current_time' => (object) [
                        'minutes' => (int) floor(($game['chT'] - $game['dateT']) / 1000 / 60),
                        'seconds' => (int) ($game['chT'] - $game['dateT']) / 1000 % 60,
                    ],

                    'sport' => self::mapSportAttribute($game['sportId']),
                    'competition' => (object) [ 'id' => $game['compId'] ],

                    'teams' => (object) [
                        'home' => (object) [
                            'id' => $game['side1Id'],
                            'name' => $game['side1'],
                            'score' => $game['score1'],
                            'red_cards' => $game['rc1'],
                        ],
                        'away' => (object) [
                            'id' => $game['side2Id'],
                            'name' => $game['side2'],
                            'score' => $game['score2'],
                            'red_cards' => $game['rc2'],
                        ]
                    ],

                    'periods' => (object) array_map(function($period) {
                        return (object) [
                            'name' => $period['c'],
                            'score1' => $period['s1'], 
                            'score2' => $period['s2'],
                            'sh' => (boolean) $period['sh']
                        ];
                    }, collect($game['periods'])->keyBy('c')->toArray()),

                    'original' => (object) $game,
                ];
            }, $array);
        }

        private function mapEventsShort($array) {
            return array_map(function($game) { 
                return (object) [
                    'id' => $game['id'],

                    'status' => $game['status'],
                    'status_live' => $game['status'],

                    'name' => $game['side1'] .' - '. $game['side2'],
                    'scores' => $game['score1'] .':'. $game['score2'],

                    'started_at' => $game['dateT'] / 1000,
                    'updated_at' => $game['chT'] / 1000,

                    'current_time' => [
                        'minutes' => (int) floor(($game['chT'] - $game['dateT']) / 1000 / 60),
                        'seconds' => (int) ($game['chT'] - $game['dateT']) / 1000 % 60,
                    ],

                    'teams' => (object) [
                        'home' => (object) [
                            'name' => $game['side1'],
                            'red_cards' => $game['rc1'],
                            'score' => $game['score1'],                            
                        ],
                        'away' => (object) [
                            'name' => $game['side2'],
                            'red_cards' => $game['rc2'],
                            'score' => $game['score2'],
                        ]
                    ],

                    // 'current_period' => self::

                    'periods' => self::mapPeriods(
                        collect(
                            $game['periods']
                        )->keyBy('c')->toArray()
                    ),

                    // 'original' => (object) $game,
                ];
            }, $array);
        }

        private function mapEvent($array) {            
            return (object) [
                'id' => $array['id'],
                'started_at' => $array['dateT'] / 1000,
                'updated_at' => $array['chT'] / 1000,

                // 'events' => $array['events'],
                // 'stats' => $array['stats'],

                'sport' => self::mapSportAttribute($array['sportId']),
                'country' => (object) [
                    'id' => $array['compCountryId'],
                    'name' => $array['compCountryName'],
                ],
                'competition' => (object) [
                    'id' => $array['compId'],
                    'name' => $array['compName'],
                    'has_standings' => (boolean) $array['compStandings'],
                ],

                'teams' => (object) [
                    'home' => (object) [
                        'id' => $array['side1Id'],
                        'name' => $array['side1'],
                        'score' => $array['score1'],
                        'red_cards' => $array['rc1'],

                        // 'players' => [
                        //     // +"lu1": []
                        //     // +"bn1": []
                        //     // +"sub1": []
                        // ]
                    ],
                    'away' => (object) [
                        'id' => $array['side2Id'],
                        'name' => $array['side2'],
                        'score' => $array['score2'],
                        'red_cards' => $array['rc2'],

                        // 'players' => [
                        //     // +"lu2": []
                        //     // +"bn2": []
                        //     // +"sub2": []
                        // ]
                    ],
                ],

                'original' => (object) $array,
            ];
        }

        /////////////////////////////////////////////////////////////////////////////////////////////////

        private function mapSportAttribute($id) {
            return (object) [
                'id' => $id,
                'name' => $this->translation->sportName->{$id},
            ];
        }

        private function mapEventStatus($string) {
            $statuses = [];

            return $statuses[$string];
        }

        private function mapPeriodStatus($string) {
            dd($this->translation);
            $periods = [];

            return $periods[$string];
        }

        private function mapPeriods($array) {
            return (object) array_map(function($period) {
                return (object) [
                    'name' => $period['c'],
                    'score1' => $period['s1'], 
                    'score2' => $period['s2'],
                    'is_finished' => (boolean) $period['sh']
                ];
            }, $array);
        }
    }