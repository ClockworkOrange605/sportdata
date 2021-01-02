<?php
    namespace SportData\Clients\BetBoom;

    use Illuminate\Http\Client\PendingRequest as HttpClient;
    use GuzzleHttp\Cookie\FileCookieJar;

    class Betting
    {
        private $client;

        public function __construct()
        {
            $this->client = new HttpClient;
            $this->client->baseUrl(env('BETBOOM_HOST'));
            $this->client->withOptions([
                'cookies' => new FileCookieJar(
                    SPORTDATA_STORAGE_PATH . '/app/betboom/cookies.json',
                true),
            ]);
        }

        public function makeBet($eventId, $oddId, $oddValue, $amount = 50)
        {
            $response = $this->client->post('Betting/QuickBet', [
                "eventId" => $eventId,
                "stakeId" => $oddId,
                "betAmount" => $amount,
                "fucktor" => $oddValue,
                "matchIsLive" => true,
                "isSuperTip" => false
            ]);

            $response = $response->json();

            return (object) [
                'coupon_id' => $response["OrderNumber"],
                'type' => $response['BetTypeName'],
                'amount' => $response['BetAmount'],               

                // +"SystemIndex": 0
                // +"TransactionId": 0
                // +"UserId": 6234221

                'status' => $response['StatusMessage']['success'],
                'status_code' => $response['StatusMessage']['Code'],
                'status_message' => $response['StatusMessage']['Status'],

                'error_code' => $response['StatusException']['ErrorCode'],
                'error_message' => $response['StatusException']['ErrorMessage'],

                // 'original' => (object) $response
            ];
        }

        public function getBets(string $fromDate, string $untilDate)
        {
            $response = $this->client->post('Account/GetUserOrders', [
                    "startDate" => $fromDate,
                    "endDate" => $untilDate,

                    "statusFilter" => 1, //<option value="1">Все ставки</option><option value="2">Выигрыш</option><option value="3">Проигрыш</option><option value="4">Не рассчитан</option></select>
                    "timeFilter" => 1,
                    "isDate" => true,
                    
                    "IsBetshopCash" => false,
                    "checkNumber" => "",
                    "disableCache" => true
                ]);

            $orders = array_map(function($order) {
                return (object) [
                    'id' => $order['N'],
                    'date' => $order['Dt'],
                    'bet_amount' => $order['BA'],
                    'win_amount' => $order['WA'],
                    'cancel_amount' => $order['CA'],
                    'possible_win_amout' => $order['CW'],                    
                    'is_win' => $order['W'],                   

                    'orignal' => (object) $order
                ];
            }, $response->json());

            return collect($orders);
        }

        public function getBet(string $betId) 
        {
            $response = $this->client->post('Account/GetUserOrderBets', [
                "orderNumber" => $betId,
                "gameType" => 0,
                "betNumber" => -1,
                "isRfId" => false
            ]);

            return (object) $response->json();
        }

        public function getBetCancelAmount(string $betId)
        {
            $response = $this->client->post('Account/GetOrderAmountForCashout', [
                "orderNumber" => $betId,
                "isRfid" => false
            ]);

            $item = $response->json();

            return (object) [
                'status' => $item["ErrorMessage"],
                'bet_odd' => $item["TotalOdds"],
                'bet_amount' => $item["InitialOrderAmount"],
                'cancel_odd' => $item["CashoutCoefficient"],
                'cancel_amount' => $item["FullCashoutAmount"]
            ];
        }

        public function cancelBet(string $betId, $amount)
        {
            $response = $this->client->post('Account/ConfirmCashout', [
                "orderNumber" => $betId,
                "cashoutAmount" => $amount,
                "fullCashoutAmount" => $amount,
                "agreeChanges" => false
            ]);

            $item = $response->json();

            return (object) [
                'success' => $item['CashoutSuccess'],
                'status' => $item['Exception'],
                'amount' => $item['OrderAmount'],
                // '' => $item['TotalCashoutAmount'],
                'original' => $item
            ];
        }

    }