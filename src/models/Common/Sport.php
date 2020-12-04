<?php
    namespace SportData\Models\Common;

    use Illuminate\Database\Eloquent\Model;

    class Sport extends Model
    {
        protected $fillable = ['name'];
        
        public $timestamps = false;

        public function countries()
        {
            return $this->hasMany('SportData\Models\Common\Country');
        }

        public function sources()
        {
            return $this->morphToMany('SportData\Models\Source', 
                'sources_pivot', null, 'entity_id', 'source_id'
            )->withPivot('external_id');
        }
    }