<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Request extends Model {

    public function company() {

        return $this->belongsTo(Company::class, 'company_id');
    }

    public function allByCompanyId($companyId) {

        $requests = DB::table('requests')
                ->where('company_id', '=', $companyId)
                ->get();

        return $requests;
    }

}
