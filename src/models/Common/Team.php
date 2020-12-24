<?php
    namespace SportData\Models\Common;

    use Illuminate\Database\Eloquent\Model;

    class Team extends Model
    {
        protected $fillable = ['league_id', 'name'];

        public $timestamps = false;

        public function leagues()
        {
            return $this->belongsTo(\SportData\Models\Common\League::class);
        }
    }