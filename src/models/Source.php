<?php
    namespace SportData\Models;

    use Illuminate\Database\Eloquent\Model;

    class Source extends Model
    {
        protected $fillable = ['name'];

        public $timestamps = false;

        public function sports()
        {
            return $this->morphedByMany('SportData\Models\Common\Sport', 
                'sources_pivot', null, 'source_id', 'entity_id'
            )->withPivot('external_id');
        }

        public function countries()
        {
            return $this->morphedByMany('SportData\Models\Common\Country', 
                'sources_pivot', null, 'source_id', 'entity_id'
            )->withPivot('external_id');
        }

        public function leagues()
        {
            return $this->morphedByMany('SportData\Models\Common\League', 
                'sources_pivot', null, 'source_id', 'entity_id'
            )->withPivot('external_id');
        }

        public function odds()
        {
            return $this->morphedByMany('SportData\Models\Common\Odd', 
                'sources_pivot', null, 'source_id', 'entity_id'
            )->withPivot('external_id');
        }

        public function events()
        {
            return $this->morphedByMany('SportData\Models\Events\Event', 
                'sources_pivot', null, 'source_id', 'entity_id'
            )->withPivot('external_id');
        }

        public function event_odds()
        {
            return $this->morphedByMany('SportData\Models\Events\EventOdd', 
                'sources_pivot', null, 'source_id', 'entity_id'
            )->withPivot('external_id');
        }
    }