<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateProject as StoreUpdateProject;
use App\Events\ProjectVisitedEvent as ProjectVisitedEvent;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Project as Project;
use Carbon\Carbon;

class ProjectController extends Controller {

    public function __construct() {

//        $this->middleware('auth', [
//            'only' => 'create'
//        ]);
    }

    // -------------------------------------------------------------------------

    public function indexUser($userId) {

        $project = new Project;

        $projects = $project->indexUser($userId);

        $user = $this->user($userId);

        return view('project/indexUser')->with([
                    'projects' => $projects,
                    'user' => $user,
        ]);
    }

    // -------------------------------------------------------------------------

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $projects = Project::latest()->get();

        return view('project/index')->with([
                    'projects' => $projects,
                    'users' => $this->users(),
        ]);
    }

    // -------------------------------------------------------------------------

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('project/create')->with([
                    'languagesArrFile' => languagesFile(),
                    'now' => Carbon::now()->format('Y-m-d')
        ]);
    }

    // -------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StoreUpdateProject $validator) {

        // With mass assigment
        // $project = new Project($request->all());
        // Auth::user()->projects()->save($project);

        $project = new Project;

        $project->name = $request['name'];

        $project->created_by = Auth::user()->id;

        $project->language = $request['language'];

        $project->save();

        // redirect
        $msg = 'Successfully created project !';

        session()->flash('flash_message', $msg);

        return redirect('projects/user/' . Auth::user()->id)->with('success', $msg);
    }

    // -------------------------------------------------------------------------

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $project = Project::find($id);

        $user = $this->userByProjectId($id);

        $videoGalleries = $project->videoGalleries()->get();

        $videos = $this->videosByGalleries($videoGalleries);

        $rate = $this->getRate($id);

        event(new ProjectVisitedEvent($user, $project));

        return view('project/show')->with([
                    'project' => $project,
                    'user' => $user,
                    'videoGalleries' => $videoGalleries,
                    'videos' => $videos,
                    'rate' => $rate
        ]);
    }

    // -------------------------------------------------------------------------

    private function getRate($id) {

        if (Auth::check() == false) {
            return false;
        }

        return $this->getUserRateOnProject($id, Auth::user()->id);
    }

    // -------------------------------------------------------------------------

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project) {

        if ($this->loggedUserCreatedProject($project) == false) {
            return redirect('/');
        }

        return view("project/edit")->with([
                    'project' => $project,
                    'languagesArrFile' => languagesFile()
        ]);
    }

    // -------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, StoreUpdateProject $validator) {

        // ---------------------------------------------------------------------
        // Unnecessary since we are receiving the instance provided by
        // RouteServiceProvider.boot
        // ---------------------------------------------------------------------
        // 
        // $project = Project::find($id);
        //
        // ---------------------------------------------------------------------

        $project->name = $request['name'];

        $project->language = $request['language'];

        $project->save();

        // redirect
        $msg = 'Successfully updated project !';

        return redirect("project/$project->id/edit")->with('success', $msg);
    }

    // -------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project) {

        //$project = Project::find($id);

        $project->delete();

        return redirect('projects/user/' . Auth::user()->id);
    }

    public function rate($projectId, $raterId, $mode) {

        $project = Project::find($projectId);

        $stored = $this->storeUserProjectRate($projectId, $raterId, $mode);

        return redirect('project/' . $projectId);
    }

    /*
     * *************************************************************************
     * Private
     * *************************************************************************
     */

    private function usersWithCreatedProjects($projects_JSON) {
        //users
        $users_JSON = $this->users();
        $users_PHP = json_decode($users_JSON);
        // proj
        $projects_PHP = json_decode($projects_JSON);
        // usersWithProj
        $usersWithCreatedProjects_PHP = [];
        $usersTemp_PHP = [];
        // filter
        foreach ($projects_PHP as $key => $value) {
            $user_id = $value->created_by;
            if (in_array($user_id, $usersTemp_PHP) == false) {
                array_push($usersTemp_PHP, $user_id);
                $userInfo_JSON = $this->user($user_id);
                $userInfo_PHP = json_decode($userInfo_JSON);
                array_push($usersWithCreatedProjects_PHP, $userInfo_PHP);
            }
        }
        $usersWithCreatedProjects_JSON = json_encode($usersWithCreatedProjects_PHP);
        return $usersWithCreatedProjects_JSON;
    }

    // -------------------------------------------------------------------------

    private function userByProjectId($projectId) {
        $createdBy_JSON = DB::table('projects')
                        ->select('created_by')
                        ->where('Id', '=', $projectId)->get();
        $createdBy_PHP = json_decode($createdBy_JSON);
        $userId = $createdBy_PHP[0]->created_by;
        $user = $this->user($userId);
        return $user;
    }

    // -------------------------------------------------------------------------
//    private function getId($projectName) {
//        $userId = Auth::user()->id;
//        $id = DB::table('projects')
//                ->select('id')
//                ->where([
//                    ['name', '=', $projectName],
//                    ['created_by', '=', $userId],
//                ])
//                ->get();
//        if ($id->isEmpty()) {
//            return false;
//        }
//        return $id;
//    }
//    
    // -------------------------------------------------------------------------

    private function loggedUserCreatedProject($project) {
        return Auth::user()->id == $project->created_by;
    }

    // -------------------------------------------------------------------------

    /*
     * *************************************************************************
     * VideoService
     * *************************************************************************
     */

    public function videosByGalleries($videoGalleries) {
        return app('videosByGalleries', [
            'videoGalleries' => $videoGalleries
        ]);
    }

    /*
     * *************************************************************************
     * UserService
     * *************************************************************************
     */

    public function users() {
        return app('users');
    }

    // -------------------------------------------------------------------------

    public function user($id) {
        return app('user', [
            'id' => $id
        ]);
    }

    /*
     * *************************************************************************
     * UserProjectRatesService
     * *************************************************************************
     */

    public function storeUserProjectRate($projectId, $raterId, $mode) {
        return app('storeUserProjectRate', [
            'projectId' => $projectId,
            'raterId' => $raterId,
            'mode' => $mode
        ]);
    }

    public function getUserRateOnProject($projectId, $userId) {
        return app('getUserRateOnProject', [
            'projectId' => $projectId,
            'userId' => $userId
        ]);
    }

}
