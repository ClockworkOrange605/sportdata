<?php
    namespace SportData\Models;

    use Illuminate\Database\Eloquent\Model;

    class Odd extends Model
    {
        public $timestamps = false;
        
        protected $guarded = [];

        public function type() 
        {
            return $this->belongsTo('SportData\Models\OddType');
        }
    }

?>