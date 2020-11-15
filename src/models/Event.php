<?php
    namespace SportData\Models;

    use Illuminate\Database\Eloquent\Model;

    class Event extends Model
    {
        public $timestamps = false;
        
        protected $guarded = [];

        public function sport()
        {
            return $this->belongsTo('SportData\Models\Sport');
        }

        public function country()
        {
            return $this->belongsTo('SportData\Models\Country');
        }

        public function league()
        {
            return $this->belongsTo('SportData\Models\League');
        }

        public function odds()
        {
            return $this->hasMany('SportData\Models\Odd');
        }
    }

?>