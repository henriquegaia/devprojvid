<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model{

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function videoGalleries() {
        return $this->hasMany('App\VideoGallery');
    }

    public function indexUser($userId) {
        return DB::table('projects')
                        ->where('created_by', '=', $userId)
                        ->latest()
                        ->get();
    }

    public function createdBy($id) {
        return DB::table('projects')
                        ->where('id', '=', $id)
                        ->value('created_by');
    }

    public function getById($id) {
        return DB::table('projects')
                        ->where('id', '=', $id)
                        ->get();
    }

    public function incrementVisits() {
        return DB::table('projects')
                        ->whereId($this->id)
                        ->increment('visits');
    }

    public function incrementRatesUp() {
        return DB::table('projects')
                        ->whereId($this->id)
                        ->increment('rates_up');
    }

    public function incrementRatesDown() {
        return DB::table('projects')
                        ->whereId($this->id)
                        ->increment('rates_down');
    }

    public function decrementRatesUp() {
        return DB::table('projects')
                        ->whereId($this->id)
                        ->decrement('rates_up');
    }

    public function decrementRatesDown() {
        return DB::table('projects')
                        ->whereId($this->id)
                        ->decrement('rates_down');
    }

}
