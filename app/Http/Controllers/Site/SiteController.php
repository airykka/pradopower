<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SiteService;
use App\Http\Requests\SiteRequest;

class SiteController extends Controller
{
    protected $siteservice;

    public function __construct(SiteService $siteservice) {
        $this->siteservice = $siteservice;
    }
    

    public function index() {
        return $this->siteservice->index();
    }

    public function store(SiteRequest $request) {
        return $this->siteservice->store($request);
    }

    public function show($id) {
        return $this->siteservice->show($id);
    }

    public function update(Request $request, $id) {
        return $this->siteservice->updateSite($request, $id);
    }

    public function destroy($id) { 
        return $this->siteservice->deleteSite($id);
    }
}
