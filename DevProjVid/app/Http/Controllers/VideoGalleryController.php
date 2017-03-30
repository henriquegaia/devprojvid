<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\VideoGallery as VideoGallery;
use App\Events\CreatedGalleryEvent;

use App\Http\Requests\StoreUpdateVideoGallery as StoreUpdateVideoGallery;

class VideoGalleryController extends Controller {

    const MAX_VIDEOS_GALLERY = 3;
    const MAX_VIDEO_SIZE_MB = 50;
    const MAX_VIDEO_SIZE_B = 50 * self::BYTES_TO_MB;
    const EXTENSIONS_ALLOWED = [
        // All in lower case
        'mp4',
    ];
    const VIDEO_PREFIX = 'video';
    const VIDEO_APPEND_EDIT = '_to_edit_';
    const BYTES_TO_MB = 1000000;

    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // Public Resource Rest
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('videoGallery/index');
    }

    // -------------------------------------------------------------------------

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($projectId) {
        if ($this->wrongUserByProject($projectId)) {
            return redirect()->back();
        }
        $project = $this->project($projectId);
        return view('videoGallery/create')->with([
                    'project' => $project,
                    'videoPrefix' => self::VIDEO_PREFIX,
                    'maxVideosGallery' => self::MAX_VIDEOS_GALLERY,
                    'maxVideoSize' => self::MAX_VIDEO_SIZE_MB
        ]);
    }

    // -------------------------------------------------------------------------

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StoreUpdateVideoGallery $validator) {
        $projectId = Input::get('projectId');
        $route = '/videoGallery/create/' . $projectId;
        $status = 'unsuccess';
        // check gallery name --------------------------------------------------
        $galleryName = Input::get('name');
        $validGalleryName = $this->validGalleryName($galleryName, $projectId);
        if (!$validGalleryName) {
            $msg = 'You already have a gallery with that name for this project !';
            return redirect($route)->with($status, $msg);
        }
        // check if there are videos -------------------------------------------
        $videosArr = $this->videosToUpload();
        if (!$videosArr) {
            $msg = 'Please choose one or more videos !';
            return redirect($route)->with($status, $msg);
        }
        $errors = $this->getVideosToStoreErrors($videosArr);
        // redirect with errors ------------------------------------------------
        if (!empty($errors)) {
            $errorsView = implode(" ", $errors);
            return redirect($route)->with($status, $errorsView);
        }
        // save ----------------------------------------------------------------
        $videoGallery = new VideoGallery;
        $videoGallery->name = $galleryName;
        $videoGallery->project_id = $projectId;
        $videoGallery->save();
        $videoGalleryId = $this->lastInsertedId($projectId, $galleryName);
        $this->storeVideos($videosArr, $videoGalleryId);
        // return --------------------------------------------------------------
        $status = 'success';
        $msg = 'Ready to save to db: https://www.youtube.com/watch?v=HDxCDdZFh9g';
        // Event Testing
        // event(new CreatedGalleryEvent($videoGallery));
        return redirect($route)->with($status, $msg);
    }

    // -------------------------------------------------------------------------

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return view("videoGallery/show");
    }

    // -------------------------------------------------------------------------

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $videoGallery = VideoGallery::find($id);
        $project = $videoGallery->project;
        if ($this->wrongUserByGallery($id, $project->id)) {
            return redirect()->back();
        }
        $videos = $videoGallery->videos;
        return view('videoGallery/edit')->with([
                    'project' => $project,
                    'videoPrefix' => self::VIDEO_PREFIX . '_to_edit_',
                    'maxVideosGallery' => self::MAX_VIDEOS_GALLERY,
                    'maxVideoSize' => self::MAX_VIDEO_SIZE_MB,
                    'videoGallery' => $videoGallery,
                    'videos' => $videos
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
    public function update(Request $request, $id, StoreUpdateVideoGallery $validator) {
        if ($request['name']) {
            $videoGallery = VideoGallery::find($id);
            $videoGallery->name = $request['name'];
            $videoGallery->save();
            $msg = "Successfully updated video gallery.";
            return redirect()->back()->with('success', $msg);
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $videoGallery = VideoGallery::find($id);
        // delete videos from folder / aws
        $VideoGalleryPath = getVideosDestinationPath() . $id . '/*';
        array_map('unlink', glob($VideoGalleryPath));
        rmdir(getVideosDestinationPath() . $id);
        // delete from db
        $videoGallery->delete();
        $msg = "Successfully deleted gallery.";
        return redirect()->back()->with('success', $msg);
    }

    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // Private
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    public function updateVideos($id, Request $request) {
        $videoPrefix = self::VIDEO_PREFIX . self::VIDEO_APPEND_EDIT;
        for ($i = 1; $i <= self::MAX_VIDEOS_GALLERY; $i++) {
            $videoIndex = $videoPrefix . $i;
            $videoName = $request[$videoIndex];
            if ($videoName != null && !empty($videoName)) {
                $videosArr[] = $videoIndex;
            }
        }
        if (empty($videosArr)) {
            return redirect()->back()->with('unsuccess', 'Please choose videos.');
        }

        // check if there are errors -------------------------------------------
        $errors = $this->getVideosToStoreErrors($videosArr);
        // redirect with errors ------------------------------------------------
        if (!empty($errors)) {
            $errorsView = implode(" ", $errors);
            return redirect()->back()->with('unsuccess', $errorsView);
        }
        // save & return -------------------------------------------------------
    }

    // -------------------------------------------------------------------------

    private function getVideosToStoreErrors($videosArr) {
        $errors = [];
        // check if videos are below size limit --------------------------------
        $videosAboveLimit = $this->videosAboveSizeLimit($videosArr);
        if (is_array($videosAboveLimit)) {
            $errors[] = "Each video has size limit of "
                    . self::MAX_VIDEO_SIZE_MB . " MB.";
        }
        // check if videos have extension --------------------------------------
        $videosBadExt = $this->videosWithWrongExtension($videosArr);
        if (!empty($videosBadExt)) {
            $extns = implode(', ', self::EXTENSIONS_ALLOWED);
            $errors[] = "Extensions allowed: $extns";
        }
        return $errors;
    }

    // -------------------------------------------------------------------------

    private function videosAboveSizeLimit($videosArr) {
        $videosOverLimit = [];
        foreach ($videosArr as $video) {
            $v = Input::file($video);
            $vSize = ceil(intval($v->getSize()));
            if ($vSize == 0 || $vSize >= self::MAX_VIDEO_SIZE_B) {
                $videosOverLimit[] = $v->getClientOriginalName();
            }
        }
        if (empty($videosOverLimit)) {
            return false;
        }
        return $videosOverLimit;
    }

    // -------------------------------------------------------------------------

    private function videosWithWrongExtension($videosArr) {
        $videosBadExt = [];
        foreach ($videosArr as $video) {
            $file = Input::file($video);
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, self::EXTENSIONS_ALLOWED)) {
                $videosBadExt[] = $video;
            }
        }
        return $videosBadExt;
    }

    // -------------------------------------------------------------------------

    private function wrongUserByProject($projectId) {
        $userId = Auth::user()->id;
        $createdBy = $this->createdBy($projectId);
        if ($createdBy != $userId) {
            return true;
        }
        return false;
    }

    // -------------------------------------------------------------------------

    private function wrongUserByGallery($id, $projectId) {
        $userId = Auth::user()->id;
        $createdBy = $this->createdBy($projectId);
        if ($createdBy != $userId) {
            return true;
        }
        return false;
    }

    // -------------------------------------------------------------------------

    private function videosToUpload($append = '') {
        $videos = [];
        for ($i = 1; $i <= self::MAX_VIDEOS_GALLERY; $i++) {
            $fname = self::VIDEO_PREFIX . $append . $i;
            if (Input::file($fname)) {
                $videos[] = $fname;
            }
        }
        if (empty($videos)) {
            return false;
        }
        return $videos;
    }

    // -------------------------------------------------------------------------

    private function lastInsertedId($projectId, $galleryName) {
        $id_JSON = DB::table('video_galleries')
                ->select('id')
                ->where([
                    ['project_id', '=', $projectId],
                    ['name', '=', $galleryName],
                ])
                ->get();
        $id_PHP = json_decode($id_JSON);
        return $id_PHP[0]->id;
    }

    // -------------------------------------------------------------------------

    private function validGalleryName($galleryName, $projectId) {
        $id_JSON = DB::table('video_galleries')
                ->select('id')
                ->where([
                    ['project_id', '=', $projectId],
                    ['name', '=', $galleryName],
                ])
                ->get();
        $id_PHP = json_decode($id_JSON);
        if (count($id_PHP) > 0) {
            return false;
        }
        return true;
    }

    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // Calls To Services
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    /*
     * *************************************************************************
     * ProjectService
     * *************************************************************************
     */

    public function createdBy($projectId) {
        return app('projectCreatedBy', [
            'projectId' => $projectId
        ]);
    }

    // -------------------------------------------------------------------------

    public function project($id) {
        return app('project', [
            'id' => $id
        ]);
    }

    /*
     * *************************************************************************
     * VideoService
     * *************************************************************************
     */

    public function storeVideos($videosArr, $videoGalleryId) {
        return app('storeVideos', [
            'videosArr' => $videosArr,
            'videoGalleryId' => $videoGalleryId
        ]);
    }

}
