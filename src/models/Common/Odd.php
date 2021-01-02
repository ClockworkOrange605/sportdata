<?php
    namespace SportData\Models\Common;

    use Illuminate\Database\Eloquent\Model;

    class Odd extends Model
    {
        use \SportData\Models\Sourceable;
        
        protected $fillable = ['name'];
        
        public $timestamps = false;
    }