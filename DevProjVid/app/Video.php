<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class Video extends Model {

    public function getDestinationPath(){
        return getVideosDestinationPath();
    }
    public function videoGallery() {
        return $this->belongsTo(VideoGallery::class, 'video_gallery_id');
    }

    public function getById($id) {
        return $this->find($id);
    }

    public function getAll() {
        return $this->all();
    }

    public function getAllByVideoGalleryId($video_gallery_id) {
        return $this->where('video_gallery_id', '=', $video_gallery_id)->get();
    }

    public function storeVideos($videosArr, $videoGalleryId) {
        foreach ($videosArr as $videoName) {
            $this->storeVideo($videoName, $videoGalleryId);
        }
    }

    private function storeVideo($videoName, $videoGalleryId) {
        
        $video = new Video;
        
        $video->video_gallery_id = $videoGalleryId;
        
        $v = Input::file($videoName);
        
        $video->size_bytes = $v->getSize();
        
        $video->name = $v->getClientOriginalName();
        
        $video->extension = $v->getClientOriginalExtension();
        
        $destinationPath = $this->getDestinationPath() . "$videoGalleryId";
        
        Input::file($videoName)->move($destinationPath, $v->getClientOriginalName());
        
        return $video->save();
    }

    public function videosByGalleries($videoGalleries) {
        $videos = [];
        
        foreach ($videoGalleries as $videoGallery) {
            
            $videoGalleryId = $videoGallery->id;
            
            $videos[$videoGalleryId] = $this->videosByGallery($videoGalleryId);
        }
        
        return $videos;
    }

    public function videosByGallery($videoGalleryId) {
        
        $v = DB::table('videos')
                ->where('video_gallery_id', '=', $videoGalleryId)
                ->get();
        
        return json_decode($v);
    }

}
