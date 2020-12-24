<?php
    namespace SportData\Models\Common;

    use Illuminate\Database\Eloquent\Model;

    class Sport extends Model
    {
        use \SportData\Models\Sourceable;
        
        protected $fillable = ['name'];
        
        public $timestamps = false;

        public function countries()
        {
            return $this->hasMany(\SportData\Models\Common\Country::class);
        }
    }