<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rss;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Analytics;
use Spatie\Analytics\Period;

class RssController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fetch the most visited pages for today and the past week
        $row1 = Analytics::fetchMostVisitedPages(Period::days(7));

        //fetch visitors and page views for the past week
        $row2 = Analytics::fetchVisitorsAndPageViews(Period::days(7));

        return $row1;

        $rsses = Rss::latest()
            ->where('status', Rss::STATUS_ACTIVE)
            ->paginate(10);
            
        return $rsses;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $rsses = Rss::where('status', Rss::STATUS_ACTIVE)->get();
        $i = 1;
        foreach ($rsses as $row) {
            $param['rss_id'] = $row->id;
            $param['title'] = $row->title;
            $website_url = $row->website_url;
            $rss = new Rss();
            //validate url live
            if ($validation_URL_live = $rss->is_url_live($website_url)){
                //validate is xml
                $feed_string = file_get_contents($website_url);
                if ($feed_string && !empty($feed_string)) {
                    if ($validation_xml = $rss->is_xml($feed_string)){
                        $param['website_url'] = $website_url;
                        $param['type'] = null;
                        
                        //is type rdf
                        if($rss->is_rdf($feed_string)){
                            $param['type'] = Rss::TYPE_IS_RDF;
                            $param['feed'] = $feed_string;
                        }
                        
                        //is type rss
                        if($rss->is_rss($feed_string)){
                            $param['type'] = Rss::TYPE_IS_RSS;
                            $param['feed'] = $feed_string;
                        }
                        
                        //is type atom
                        if($rss->is_atom($feed_string)){
                            $param['type'] = Rss::TYPE_IS_ATOM;
                            $param['feed'] = $feed_string;
                        }
                        
                        if (!empty($param['type'])) {
                            //save rss
                            if ($article = $rss->create_rss($param, Rss::ACTION_UPDATE)) {
                                $i++;
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'msg' => $i
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'website_url' => 'required|string',
        ]);

        $param = $request->all();
        $website_url = $param['website_url'];
        $rss = new Rss();
        //validate url live
        if (!$validation_URL_live = $rss->is_url_live($website_url)){
            return response()->json([
                'msg' => 'Không tìm thấy đường dẫn Rss!'
            ], 422);
        }

        //validate is xml
        $feed_string = file_get_contents($website_url);
        if ($feed_string && !empty($feed_string)) {
            if (!$validation_URL_live = $rss->is_xml($feed_string)){
                // return $this->action_index('xml not found', \Input::post());
                return response()->json([
                    'msg' => 'Không phải định dạng XML!'
                ], 422);
            } else{
                $param['type'] = null;
                
                //is type rdf
                if($rss->is_rdf($feed_string)){
                    $param['type'] = Rss::TYPE_IS_RDF;
                    $param['feed'] = $feed_string;
                }
                
                //is type rss
                if($rss->is_rss($feed_string)){
                    $param['type'] = Rss::TYPE_IS_RSS;
                    $param['feed'] = $feed_string;
                }
                
                //is type atom
                if($rss->is_atom($feed_string)){
                    $param['type'] = Rss::TYPE_IS_ATOM;
                    $param['feed'] = $feed_string;
                }
                
                if (!empty($param['type'])) {
                    //save rss
                    if (!$article = $rss->create_rss($param, Rss::ACTION_CREATE)) {
                        return response()->json([
                            'msg' => 'Không thể tạo Rss!'
                        ], 422);
                    }
                } else {
                    return response()->json([
                        'msg' => 'Không thể tạo Rss!'
                    ], 422);
                }
                return response()->json([
                    'msg' => 'Tạo thành công!'
                ], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete rss
        $rss = new Rss();
        $rss->delete_rss($id);

        return response()->json([
            'msg' => 'success'
        ], 200);
    }
}
