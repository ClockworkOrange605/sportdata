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

        // public function homeEvents()
        // {
        //     return $this->hasMany(\SportData\Models\Events\Event::class, 'home_team_id');
        // }

        // public function awayEvents()
        // {
        //     return $this->belongsTo(\SportData\Models\Events\Event::class, 'away_team_id');
        // }
    }