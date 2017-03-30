<?php

namespace App\Listeners;

use App\Events\ProjectVisitedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class IncrementProjectVisits {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProjectVisitedEvent  $event
     * @return void
     */
    public function handle(ProjectVisitedEvent $event) {
        
        $project = $event->project;

        $shouldInc = $this->shouldInc($event);

        if ($shouldInc == false) {
            return;
        }

        return $project->incrementVisits();
    }

    private function shouldInc(ProjectVisitedEvent $event) {
        
        if (!Auth::check()) {
            return false;
        }

        $userIdLoggedIn = Auth::user()->id;

        $userIdProjectOwner = $event->user->id;

        if ($userIdProjectOwner == $userIdLoggedIn) {
            return false;
        }

        return true;
    }

}
