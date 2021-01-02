<?php
    namespace SportData\Clients\BetBoom;

    use Illuminate\Http\Client\PendingRequest as HttpClient;
    use GuzzleHttp\Cookie\FileCookieJar;
    use Illuminate\Support\Str;

    class Auth
    {
        private $client;

        public function __construct()
        {
            $this->client = new HttpClient;
            $this->client->withOptions([
                'cookies' => new FileCookieJar(
                    SPORTDATA_STORAGE_PATH . '/app/betboom/cookies.json', 
                true),
            ]);
        }

        public function authorize() 
        {
            $response = $this->client->asForm()->post('https://betboom.ru/auth/login', [
                'phone' => env('BETBOOM_PHONE'),
                'password' => env('BETBOOM_PASSWORD'),
            ]);

            self::__construct();

            return $response->json();
        }

        public function index() 
        {
            $response = $this->client->get('https://betboom.ru/sport');

            return $response->body();
        }        

        public function home(string $token = '-') 
        {   
            $response = $this->client->get(
                "https://sport.betboom.ru/SportsBook/Home?token={$token}&sportsBookView=&l=ru&d=d&tz=&of=0&customCssUrl="
            );
            
            return $response->body();
        }

        public static function parseToken(string $page)
        {
            return (string) Str::of($page)
                ->after("server: 'https://sport.betboom.ru/',")
                ->before("login: 'sport.showRegisterNotComplete'")
                ->trim()
                ->after("token: '")
                ->before("',");
        }
        
    }