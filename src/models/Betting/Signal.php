<?php
    namespace SportData\Models\Betting;

    use Illuminate\Database\Eloquent\Model;

    class Signal extends Model
    {
        public $fillable = ['sport_id', 'event_id', 'flag', 'code', 
            'odd_external_id', 'odd_type', 'odd_term', 'odd_value'];
    }