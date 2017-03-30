<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User as User;

class UserController extends Controller {

    public function projects() {
        
        $user = new User;
        
        $userId = Auth::user()->id;
        
        $projects = User::find($userId)->projects();
        
        $projects_JSON = json_encode($projects);
        
        $url = 'user/projects';
        
        return view($url)->with([
                    'projects' => $projects_JSON
        ]);
    }

    // -------------------------------------------------------------------------

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $user = User::find($id);

        $avatar = $user->getAvatar();

        return view('user/show')->with([
                    'user' => $user,
                    'avatar' => $avatar
        ]);
    }

    // -------------------------------------------------------------------------

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
        $user = User::find($id);
        
        return view('user/edit')->with([
                    'user' => $user
        ]);
    }

    // -------------------------------------------------------------------------


    public function updateEmail(Request $request) {
        
        $input = $request->all();
        
        $email = $input['email'];
        
        $user = new User;
        
        // Check if email already exists
        if ($this->validEmail($email) == false) {
            
            $route = '/user/' . Auth::user()->id . '/edit';
            
            $status = 'unsuccess';
            
            $msg = "That email is already in use !";
            
            return redirect($route)->with($status, $msg);
        }
        
        // Update
        $val = $user->updateEmail($email);
        
        return $this->returnAfterUpdate($val, 'email');
    }

    // -------------------------------------------------------------------------

    private function validEmail($email) {
        
        if ($email == Auth::user()->email) {
            return true;
        }
        
        if (User::where('email', '=', $email)->count() > 0) {
            return false;
        }
        
        return true;
    }

    // -------------------------------------------------------------------------


    public function updateName(Request $request) {
        
        $input = $request->all();
        
        $name = $input['name'];
        
        $user = new User;
        
        $val = $user->updateName($name);
        
        return $this->returnAfterUpdate($val, 'name');
    }

    // -------------------------------------------------------------------------

    private function returnAfterUpdate($val, $type) {
        
        $route = '/user/' . Auth::user()->id . '/edit';
        
        if ($val == false) {
            
            $status = 'unsuccess';
            
            $msg = 'Something went wrong. Please try again later !';
            
            return redirect($route)->with($status, $msg);
        }
        
        $status = 'success';
        
        $msg = "You have successfully changed your $type !";
        
        return redirect($route)->with($status, $msg);
    }

}
