<?php
    namespace SportData\Models\Common;

    use Illuminate\Database\Eloquent\Model;

    class Country extends Model
    {   
        use \SportData\Models\Sourceable;

        protected $fillable = ['sport_id', 'name'];
        
        public $timestamps = false;

        public function sport()
        {
            return $this->belongsTo(\SportData\Models\Common\Sport::class);
        }

        public function leagues()
        {
            return $this->hasMany(\SportData\Models\Common\League::class);
        }
    }