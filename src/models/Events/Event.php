<?php
    namespace SportData\Models\Events;

    use Illuminate\Database\Eloquent\Model;

    class Event extends Model
    {
        use \SportData\Models\Sourceable;

        protected $fillable = ['league_id', 'home_team_id', 'away_team_id',
            'name', 'status', 'home_score', 'away_score', 'date'];

        public $timestamps = false;

        public function league()
        {
            return $this->belongsTo(\SportData\Models\Common\League::class);
        }
    }

?>