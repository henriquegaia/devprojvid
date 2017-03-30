<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\DB;

class Company extends User {

    protected $table = 'companies';
    

    public function requests() {

        return $this->hasMany(Request::class);
    }

    public function exists($userId) {

        $result = DB::table($this->table)
                ->where('user_id', '=', $userId)
                ->get();

        return !$result->isEmpty();
    }

    public function getCompanyIdFromUserId($userId) {

        $company = DB::table($this->table)
                ->where('user_id', '=', $userId)
                ->get();

        if ($company->isEmpty()) {
            return false;
        }

        return $company[0]->id;
    }

    public function getUserIdFromCompanyId($id) {

        $company = DB::table($this->table)
                ->where('id', '=', $id)
                ->get();

        if ($company->isEmpty()) {
            return false;
        }

        return $company[0]->user_id;
    }

}
