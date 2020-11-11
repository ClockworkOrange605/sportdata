<?php 
    require __DIR__ . '/../vendor/autoload.php';

    use Illuminate\Http\Client\PendingRequest;

    $host = 'https://app.sportdataapi.com/api/v1/soccer';
    $key = '186e7690-21d6-11eb-bc95-ed96806073c4';

    // $counryID = 42; // England
    // $leagueID = 237; // Premier League
    // $seasonID = 352; // "20/21"

    // $counryID = 102; // Russia
    // $leagueID = 504; // Premier League
    // $seasonID = 1477; // "20/21"

    $counryID = 46; // France
    $leagueID = 310; // Ligue 1
    $seasonID = 455; // "20/21"

    // $counryID = 48; // Germany
    // $leagueID = 314; // Bundesliga
    // $seasonID = 496; // "20/21"

    $matches = (new PendingRequest())->withHeaders([
        'apikey' => $key
    ])->get("{$host}/matches", [
        'season_id' => $seasonID,
        //'status_id' => 3, !!! Not working
        // 'date_from' => '2020-11-01',
        // 'date_to' => '2020-11-09'
        'date_from' => '2020-11-16',
        'date_to' => '2020-11-23'
    ])->json('data');

    foreach($matches as $match){
        dump(data_get($match, 'match_start'));
        dump(data_get($match, 'status'));

        dump(data_get($match, 'home_team.name'));
        dump(data_get($match, 'away_team.name'));

        dump(data_get($match, 'stats.home_score'));
        dump(data_get($match, 'stats.away_score'));

        $odds = (new PendingRequest())->withHeaders([
            'apikey' => $key
        ])->get("{$host}/odds/{$match['match_id']}", [
            'type' => 'prematch'
            //'type' => 'inplay'
        ])->json('data');

        if(empty($odds)) continue;

        // foreach(data_get($odds, '1X2, Full Time Result.bookmakers') as $bookmaker) {
        //     dump(data_get($bookmaker, 'odds_data.home'));
        //     dump(data_get($bookmaker, 'odds_data.away'));
        //     dump(data_get($bookmaker, 'odds_data.draw'));
        //     dump(data_get($bookmaker, 'last_updated'));
        // }
        
        if(
            abs(
                dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.home'))->avg()) -
            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.away'))->avg())    
            ) > 3
        ) {

            //dd(data_get($odds, '1X2, Full Time Result'));
            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.home'))->avg());
            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.home'))->max());
            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.home'))->min());

            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.away'))->avg());
            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.away'))->max());
            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.away'))->min());

            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.draw'))->avg());
            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.draw'))->max());
            dump(collect(data_get($odds, '1X2, Full Time Result.bookmakers.*.odds_data.draw'))->min());
        }

        //dd(data_get($odds, 'Asian Handicap'));

        //dd(data_get($odds, 'Over/Under, Goal Line'));
    }
?>