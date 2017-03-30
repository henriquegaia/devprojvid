<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\User as User;
use App\Project as Project;
use App\Video as Video;
use App\UserProjectRate as UserProjectRate;
use App\Company as Company;
use App\Request as RequestModel;

class AppServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->registerUserServices();
        $this->registerProjectServices();
        $this->registerVideoServices();
        $this->registerUserProjectRateServices();
        $this->registerCompanyServices();
        $this->registerRequestServices();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        
    }

    // -------------------------------------------------------------------------

    private function registerUserServices() {
        $this->app->bind('users', function() {
            return User::all();
        });
        $this->app->bind('user', function($app, $params) {
            $id = $params['id'];
            return User::find($id);
        });
    }

    // -------------------------------------------------------------------------

    private function registerProjectServices() {
        $this->app->bind('project', function($app, $params) {
            $id = $params['id'];
            return Project::find($id);
        });
        $this->app->bind('projectCreatedBy', function($app, $params) {
            $projectId = $params['projectId'];
            $project = new Project;
            return $project->createdBy($projectId);
        });
        $this->app->bind('projectUpdateRates', function($app, $params) {

            $project = Project::find($params['projectId']);

            if ($params['incRatesUp'] == true) {
                $project->incrementRatesUp();
            }
            if ($params['incRatesDown'] == true) {
                $project->incrementRatesDown();
            }
            if ($params['decRatesUp'] == true) {
                $project->decrementRatesUp();
            }
            if ($params['decRatesDown'] == true) {
                $project->decrementRatesDown();
            }
        });
    }

    // -------------------------------------------------------------------------

    private function registerVideoServices() {
        $this->app->bind('videosByGalleries', function($app, $params) {
            $video = new Video;
            $videoGalleries = $params['videoGalleries'];
            return $video->videosByGalleries($videoGalleries);
        });

        $this->app->bind('videosByGallery', function($app, $params) {
            $video = new Video;
            $videoGalleryId = $params['videoGalleryId'];
            return $video->videosByGallery($videoGalleryId);
        });

        $this->app->bind('storeVideos', function($app, $params) {
            $video = new Video;
            $videosArr = $params['videosArr'];
            $videoGalleryId = $params['videoGalleryId'];
            return $video->storeVideos($videosArr, $videoGalleryId);
        });
    }

    // -------------------------------------------------------------------------

    private function registerUserProjectRateServices() {
        $this->app->bind('storeUserProjectRate', function($app, $params) {

            $userProjectRate = DB::table('user_project_rates')
                    ->where('project_id', '=', $params['projectId'])
                    ->where('user_id', '=', $params['raterId'])
                    ->first();

            if (empty($userProjectRate)) {

                $this->storeUserProjectRate($params);
            } else {

                $this->updateUserProjectRate($userProjectRate, $params);
            }
        });

        $this->app->bind('getUserRateOnProject', function($app, $params) {
            $userProjectRate = DB::table('user_project_rates')
                    ->where('project_id', '=', $params['projectId'])
                    ->where('user_id', '=', $params['userId'])
                    ->first();

            if (empty($userProjectRate)) {
                return false;
            }
            if ($userProjectRate->rated_up == 1) {
                return 'rated_up';
            }
            if ($userProjectRate->rated_down == 1) {
                return 'rated_down';
            }
        });
    }

    // -------------------------------------------------------------------------

    private function updateUserProjectRate($userProjectRate, $params) {

        switch ($params['mode']) {

            case 'up':

                $this->updateUserProjectRateToUp($userProjectRate, $params);

                break;

            case 'down':

                $this->updateUserProjectRateToDown($userProjectRate, $params);

                break;

            default:

                break;
        }
    }

    // -------------------------------------------------------------------------

    private function updateUserProjectRateToUp($userProjectRate, $params) {

        $rated_up = $userProjectRate->rated_up;

        $rated_down = $userProjectRate->rated_down;

        if ($rated_up == 1 && $rated_down == 0) {

            $this->updateUserProjectRate_CancelRatedUp($userProjectRate, $params);
        } else if ($rated_up == 0 && $rated_down == 1) {

            $this->updateUserProjectRate_DownToUp($userProjectRate, $params);
        } else if ($rated_up == 1 && $rated_down == 1) {

            die('rated_up rated_down: both are 1');
        }
    }

    // -------------------------------------------------------------------------

    private function updateUserProjectRateToDown($userProjectRate, $params) {

        $rated_up = $userProjectRate->rated_up;

        $rated_down = $userProjectRate->rated_down;

        if ($rated_up == 1 && $rated_down == 0) {

            $this->updateUserProjectRate_UpToDown($userProjectRate, $params);
        } else if ($rated_up == 0 && $rated_down == 1) {

            $this->updateUserProjectRate_CancelRatedDown($userProjectRate, $params);
        } else if ($rated_up == 1 && $rated_down == 1) {

            die('rated_up rated_down: both are 1');
        }
    }

    // -------------------------------------------------------------------------

    private function updateUserProjectRate_UpToDown($userProjectRate, $params) {

        $userProjectRate = UserProjectRate::find($userProjectRate->id);

        $userProjectRate->rated_up = 0;

        $userProjectRate->rated_down = 1;

        $userProjectRate->save();

        // TODO: projects (Inc & Dec)

        app('projectUpdateRates', [
            'projectId' => $userProjectRate->project_id,
            'incRatesUp' => false,
            'incRatesDown' => true,
            'decRatesUp' => true,
            'decRatesDown' => false
        ]);
    }

    // -------------------------------------------------------------------------

    private function updateUserProjectRate_DownToUp($userProjectRate, $params) {

        $userProjectRate = UserProjectRate::find($userProjectRate->id);

        $userProjectRate->rated_up = 1;

        $userProjectRate->rated_down = 0;

        $userProjectRate->save();

        // TODO: projects (Inc & Dec)

        app('projectUpdateRates', [
            'projectId' => $userProjectRate->project_id,
            'incRatesUp' => true,
            'incRatesDown' => false,
            'decRatesUp' => false,
            'decRatesDown' => true
        ]);
    }

    // -------------------------------------------------------------------------

    private function updateUserProjectRate_CancelRatedUp($userProjectRate, $params) {

        $userProjectRate = UserProjectRate::find($userProjectRate->id);

        $userProjectRate->delete();

        // TODO: Dec (projects) 

        app('projectUpdateRates', [
            'projectId' => $userProjectRate->project_id,
            'incRatesUp' => false,
            'incRatesDown' => false,
            'decRatesUp' => true,
            'decRatesDown' => false
        ]);
    }

    // -------------------------------------------------------------------------

    private function updateUserProjectRate_CancelRatedDown($userProjectRate, $params) {

        $userProjectRate = UserProjectRate::find($userProjectRate->id);

        $userProjectRate->delete();

        // TODO: Dec (projects) 

        app('projectUpdateRates', [
            'projectId' => $userProjectRate->project_id,
            'incRatesUp' => false,
            'incRatesDown' => false,
            'decRatesUp' => false,
            'decRatesDown' => true
        ]);
    }

    // -------------------------------------------------------------------------

    private function storeUserProjectRate($params) {

        $userProjectRate = new UserProjectRate;

        $userProjectRate->project_id = $params['projectId'];

        $userProjectRate->user_id = $params['raterId'];

        switch ($params['mode']) {

            case 'up':

                $userProjectRate->rated_up = 1;

                $userProjectRate->rated_down = 0;

                app('projectUpdateRates', [
                    'projectId' => $userProjectRate->project_id,
                    'incRatesUp' => true,
                    'incRatesDown' => false,
                    'decRatesUp' => false,
                    'decRatesDown' => false
                ]);

                break;

            case 'down':

                $userProjectRate->rated_down = 1;

                $userProjectRate->rated_up = 0;

                app('projectUpdateRates', [
                    'projectId' => $userProjectRate->project_id,
                    'incRatesUp' => false,
                    'incRatesDown' => true,
                    'decRatesUp' => false,
                    'decRatesDown' => false
                ]);

                break;

            default:

                break;
        }

        return $userProjectRate->save();
    }

    public function registerCompanyServices() {

        $this->app->bind('getCompanyIdFromUserId', function($app, $params) {

            $company = new Company();

            return $company->getCompanyIdFromUserId($params['userId']);
        });

        $this->app->bind('getUserIdFromCompanyId', function($app, $params) {

            $company = new Company();

            return $company->getUserIdFromCompanyId($params['companyId']);
        });
    }

    public function registerRequestServices() {

        $this->app->bind('companiesIdsWithRequestsToUserIdArray', function($app, $params) {

            $requests = RequestModel::all();

            $companiesUserId = [];

            foreach ($requests as $request) {

                if (!array_key_exists($request->company_id, $companiesUserId)) {

                    $userId = app('getUserIdFromCompanyId', [
                        'companyId' => $request->company_id
                    ]);

                    $companiesUserId[$request->company_id] = $userId;
                }
            }

            return $companiesUserId;
        });
    }

}
