<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Request as RequestModel;
use App\Project;

class WelcomeController extends Controller {

    public function index() {

        $requests = RequestModel::latest()
                ->get();

        $projects = Project::latest()
                ->get();

        $users = app('users');

        $companiesIdToUserId = app('companiesIdsWithRequestsToUserIdArray');

        return view('welcome.index', [
            'requests' => $requests,
            'projects' => $projects,
            'users' => $users,
            'companiesIdToUserId' => $companiesIdToUserId
        ]);
    }

}
