<?php
    namespace SportData\Models\Common;

    use Illuminate\Database\Eloquent\Model;

    class League extends Model
    {
        use \SportData\Models\Sourceable;

        protected $fillable = ['country_id', 'name'];
        
        public $timestamps = false;

        public function country()
        {
            return $this->belongsTo(\SportData\Models\Common\Country::class);
        }
    }