<?php
    require __DIR__ . '/../src/bootstrap.php';

    use SportData\Clients\BetBoom\BetBoom;
    use SportData\Models\Source;
    use SportData\Models\Events\Event;

    $source_name = 'BetBoom';
    $sport_id = 1;
    $odd_types = [1];
    $period_name = 'Finished'; //Interrupted

    $client = new BetBoom;
    $source = Source::where('name', $source_name)->first();

    $events = $client->getLiveEvents(
        $sport_id, $odd_types
    );

    $events
        ->where('period_name', 'Finished')
        ->each(function($event) use($source) {
        $added_event = Event::findBySourceId($source->id, $event->source->id);

        if(!empty($added_event)) {
            $added_event->fill([
                'status' => 'finished',
                'home_score' => $event->teams->home->score,
                'away_score' => $event->teams->away->score,
            ])->save();
        }
    });

    dd(microtime(true) - SPORTDATA_START);