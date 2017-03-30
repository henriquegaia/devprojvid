<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Match;

class MatchController extends Controller {

    public function index() {
        return Match::all();
    }

}
