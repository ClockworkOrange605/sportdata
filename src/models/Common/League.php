<?php
    namespace SportData\Models\Common;

    use Illuminate\Database\Eloquent\Model;

    class League extends Model
    {
        protected $fillable = ['country_id', 'name'];
        
        public $timestamps = false;

        public function country()
        {
            return $this->belongsTo(\SportData\Models\Common\Country::class);
        }

        public function sources()
        {
            return $this->morphToMany('SportData\Models\Source', 
                'sources_pivot', null, 'entity_id', 'source_id'
            )->withPivot('external_id');
        }
    }