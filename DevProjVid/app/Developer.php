<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\DB;

class Developer extends User {

    protected $table = 'developers';
    
    public function exists($userId){
        
        $result = DB::table($this->table)
                ->where('user_id','=',$userId)
                ->get();
        
        return !$result->isEmpty();

    }

}
