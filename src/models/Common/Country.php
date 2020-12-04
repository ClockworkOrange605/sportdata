<?php
    namespace SportData\Models\Common;

    use Illuminate\Database\Eloquent\Model;

    class Country extends Model
    {   
        protected $fillable = ['sport_id', 'name'];
        
        public $timestamps = false;

        public function sport()
        {
            return $this->belongsTo(\SportData\Models\Common\Sport::class);
        }

        public function leagues()
        {
            return $this->hasMany('SportData\Models\Common\League');
        }

        public function sources()
        {
            return $this->morphToMany('SportData\Models\Source', 
                'sources_pivot', null, 'entity_id', 'source_id'
            )->withPivot('external_id');
        }
    }