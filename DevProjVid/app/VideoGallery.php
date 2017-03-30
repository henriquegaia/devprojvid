<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoGallery extends Model {

    public function project() {
        return $this->belongsTo('App\Project', 'project_id');
    }

    public function videos(){
        return $this->hasMany(Video::class);
    }
}
