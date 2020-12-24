<?php
    namespace SportData\Models;

    trait Sourceable
    {
        public function sources()
        {
            return $this->morphToMany('SportData\Models\Source', 
                'sources_pivot', null, 'entity_id', 'source_id'
            )->withPivot('external_id');
        }

        public static function findBySourceId(int $sourceId, int $sourceExternalId)
        {
            return self::whereHas('sources', function($query) use($sourceExternalId, $sourceId) {
                $query->where('sources_pivots.source_id', $sourceId)
                     ->where('sources_pivots.external_id', $sourceExternalId);
            })->first();
        }

        public function getExternalId(int $sourceId) {
            return $this->sources()
                        ->where('source_id', $sourceId)
                        ->first()->pivot->external_id;
        }        
    }