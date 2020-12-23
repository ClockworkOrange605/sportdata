<?php
    namespace SportData\Clients\BetBoom;

    use Illuminate\Http\Client\PendingRequest as HttpClient;
    use GuzzleHttp\Cookie\FileCookieJar;

    class Auth
    {
        private $client;
        private $cookies;

        public function __construct()
        {
            $this->client = new HttpClient;
            // $this->client->baseUrl(env('BETBOOM_HOST'));

            $this->cookies = new FileCookieJar(__DIR__ .  '/../../../storage/cookie.json', true);
            $this->client->withOptions([
                'cookies' =>$this->cookies,
            ]);
        }

        public function index() 
        {
            $response = $this->client->get('https://betboom.ru/sport');

            // dump(
            //     $response->cookies(),
            // );

            return $response->body();
        }

        public function authorize() 
        {
            $response = $this->client->asForm()->post('https://betboom.ru/auth/login', [
                'phone' => env('BETBOOM_PHONE'),
                'password' => env('BETBOOM_PASSWORD'),
            ]);

            // dump(
            //     $response->cookies(),
            // );

            return $response->json('status') == 'success' ? true : false;
        }

        public function home($token) 
        {
            
            $response = $this->client->get(
                "https://sport.betboom.ru/SportsBook/Home?token={$token}&sportsBookView=&l=ru&d=d&tz=&of=0&customCssUrl=");

                    // dump(
                    //     $response->cookies(),
                    // );
            
            return $response->body();
        }

        public function getCoupon() {
            $response = $this->client->post('https://sport.betboom.ru/Betting/GetCoupon');

            // dump(
                // $response->cookies(),
            //     $response->body(),
            //     $response->json(),
            // );

            return (object) $response->json();
        }

        public function getOrders(string $fromDate, string $untilDate)
        {
            $response = $this->client->post('https://sport.betboom.ru/Account/GetUserOrders', [
                    "startDate" => $fromDate,
                    "endDate" => $untilDate,

                    "statusFilter" => 1,
                    "timeFilter" => 1,
                    "isDate" => true,
                    
                    "IsBetshopCash" => false,
                    "checkNumber" => "",
                    "disableCache" => false
                ]);

            return collect($response->json());
        }

        public function setMagic()
        {
            $response = $this->client->post('https://sport.betboom.ru/Events/SetP0Tf', [
                'timeFilter' => 0
            ]);

            dump($response->body());

            return $response->json();
        }

    }