<?php

function getBaseUrl() {
    return "http://localhost/_projetos/DevProjVid/DevProjVid";
}

function getVideosDestinationPath() {
    return '../database/uploads/video_gallery/';
}

function prettifyArray($array) {
    echo '<pre>' . print_r($array, true) . '</pre>';
}

function isDeveloper() {

    if (!Auth::check()) {
        return false;
    }

    $userId = Auth::id();

    $developer = new \App\Developer();

    return $developer->exists($userId);
}

function isCompany() {

    if (!Auth::check()) {
        return false;
    }

    $userId = Auth::id();

    $company = new \App\Company();

    return $company->exists($userId);
}

function languagesFile() {
    
    $pathToBase = dirname(dirname(__FILE__));
    
    $pathToBase = str_replace('\\', '/', $pathToBase);
    
    $pathToViews = $pathToBase . '/resources/views';
       
    $languagesSelectFile = $pathToViews . '/language/languages.php';
    
    $languagesArrFile = $pathToViews . '/language/languagesArr.php';
    
    return $languagesArrFile;
}
