<?php
namespace App\Services;

use App\Models\Site\Site;
use App\Http\Resources\SiteResource;

class SiteService 
{
  protected $site;
  protected $api;

  public function __construct(Site $site) {
    $this->site = $site;
  }

  /**
   * Get all saved Sites
   */
  public function index() {
    $siteList = $this->site->orderBy('name', 'ASC')->get();
    return response()->json(['status' => true, 'message' => 'success', 'data' => SiteResource::collection($siteList)], 200);
  }

  /** 
   * Store new site
   */
  public function store($request) {
    \Log::info($request->all());
    $site =$this->site->create([
      'name' => $request->name,
      'currency' => $request->currency,
      'phone_number' => $request->phone_number
    ]);

    if($site) {
      return response()->json(['status' => true, 'message' => 'Site Created', 'data' => $site], 201);
    }

    return response()->json(['status' => false, 'message' => 'Could not create site'], 422);
  }

  /**
   * Get specific site with the site ID
   */
  public function show($siteId) {
    $site = $this->site->find($siteId);

    if(!empty($site)) {
      return \response()->json(['status' => true, 'message' => 'success', 'data' => $site], 200);
    }

    return \response()->json(['status' => false, 'message' => 'Site not found'], 422);
  }

  /**
   * Update site
  */
  public function updateSite($request, $id) {
    $site = $this->site->find($id);
    if($site) {
      $site->update([
        'name' => $request->name,
        'currency' => $request->currency,
        'phone_number' => $request->phone_number
      ]);
      return \response()->json(['status' => true, 'message' => 'success', 'data' => $site], 200);
    }
    return \response()->json(['status' => false, 'message' => 'Site not found'], 422);    
  }

  /**
   * Delete saved Site
   */
  public function deleteSite($siteId) {
    $site = $this->site->find($siteId);

    if($site) {
      $site->delete();
      return \response()->json(['status' => true, 'message' => 'success', 'data' => []]);
    }

    return \response()->json(['status' => false, 'message' => 'Site not found']);
  }

}


