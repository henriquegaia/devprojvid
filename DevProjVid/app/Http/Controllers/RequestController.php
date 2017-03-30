<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\StoreUpdateRequest;
use App\Request as RequestModel;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller {

    public function index() {

        $requests = RequestModel::latest()->get();

        $companiesIdToUserId = app('companiesIdsWithRequestsToUserIdArray');

        return view('request/index')->with([
                    'requests' => $requests,
                    'companiesIdToUserId' => $companiesIdToUserId
        ]);
    }

    // -------------------------------------------------------------------------

    public function indexByCompany($companyId) {

        $request = new RequestModel();

        $requests = $request->allByCompanyId($companyId);

        $userId = $this->getUserIdFromCompanyId($companyId);

        $name = User::find($userId)->name;

        $userOwnsRequests = $this->userOwnsRequests($companyId);

        return view('request/indexByCompany', [
            'name' => $name,
            'requests' => $requests,
            'userOwnsRequests' => $userOwnsRequests,
            'userId' => $userId
        ]);
    }

    // -------------------------------------------------------------------------

    public function show($id) {

        $request = RequestModel::find($id);

        $companyId = $request->company_id;

        $userId = $this->getUserIdFromCompanyId($companyId);

        $userOwnsRequest = $this->userOwnsRequest($request);

        return view('request/show')->with([
                    'request' => $request,
                    'userOwnsRequest' => $userOwnsRequest,
                    'companyName' => User::find($userId)->name,
                    'companyId' => $companyId,
                    'userId' => $userId
        ]);
    }

    // -------------------------------------------------------------------------

    public function create() {

        return view('request/create', [
            'languagesArrFile' => languagesFile()
        ]);
    }

    // -------------------------------------------------------------------------

    public function store(Request $request, StoreUpdateRequest $validator) {

        try {

            $requestModel = new RequestModel();

            $requestModel->company_id = $this->getCompanyIdFromUserId(Auth::id());

            $requestModel->language = $request['language'];

            $requestModel->save();

            return redirect('request/create')->with('success', 'Request created!');
        } catch (Exception $ex) {

            return redirect('request/create')->with('unsuccess', 'Request not created!');
        }
    }

    // -------------------------------------------------------------------------

    public function edit($id) {

        $request = RequestModel::find($id);

        return view('request/edit', [
            'request' => $request
        ]);
    }

    // -------------------------------------------------------------------------

    public function update($id, Request $request, StoreUpdateRequest $validator) {

        try {

            $requestModel = RequestModel::find($id);

            $requestModel->language = $request['language'];

            $requestModel->save();

            $status = 'success';

            $msg = 'Request updated!';
        } catch (Exception $ex) {

            $status = 'unsuccess';

            $msg = 'Request not updated!';
        }

        return redirect("request/$id/edit")->with([
                    $status => $msg
        ]);
    }

    // -------------------------------------------------------------------------

    public function destroy($id) {

        $request = RequestModel::find($id);

        $companyId = $request->company_id;

        $request->delete();

        return $this->indexByCompany($companyId);
    }

    // -------------------------------------------------------------------------

    public function userOwnsRequests($companyId) {

        if (!Auth::check()) {
            return false;
        }

        $userId = Auth::user()->id;

        $companyIdLogged = app('getCompanyIdFromUserId', [
            'userId' => $userId
        ]);

        if ($companyIdLogged != $companyId) {
            return false;
        }

        return true;
    }

    // -------------------------------------------------------------------------

    private function userOwnsRequest($request) {

        if (!Auth::check()) {
            return false;
        }

        $userId = Auth::user()->id;

        $companyId = $this->getCompanyIdFromUserId($userId);

        if (!$companyId) {
            return false;
        }

        if ($request->company_id != $companyId) {
            return false;
        }

        return true;
    }

    // -------------------------------------------------------------------------

    public function companyIdToUserIdArray() {

        $requests = RequestModel::all();

        $companiesUserId = [];

        foreach ($requests as $request) {

            if (!array_key_exists($request->company_id, $companiesUserId)) {

                $userId = $this->getUserIdFromCompanyId($request->company_id);

                $companiesUserId[$request->company_id] = $userId;
            }
        }

        return $companiesUserId;
    }

    // -------------------------------------------------------------------------

    private function getCompanyIdFromUserId($userId) {

        return app('getCompanyIdFromUserId', [
            'userId' => $userId
        ]);
    }

    // -------------------------------------------------------------------------

    private function getUserIdFromCompanyId($companyId) {

        return app('getUserIdFromCompanyId', [
            'companyId' => $companyId
        ]);
    }

    // -------------------------------------------------------------------------
}
