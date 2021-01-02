<?php
    namespace SportData\Models\Events;

    use Illuminate\Database\Eloquent\Model;

    class EventOdd extends Model
    {
        use \SportData\Models\Sourceable;
        
        protected $fillable = [
            'type_id', 'event_id', 'type', 'name', 'value', 'condition'
        ];
        
        public $timestamps = false;
    }