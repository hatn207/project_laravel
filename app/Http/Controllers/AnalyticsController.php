<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Analytics;
use Spatie\Analytics\Period;
use Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        switch ($request->input('search')) {
            case '1month':
                $period = Period::months(1);
                break;

            case '7days':
                $period = Period::days(7);
                break;
            
            default:
                $period = Period::days(10);
                break;
        }
        // thong ke top content
        $analyticsData = Analytics::performQuery(
            $period,
            'ga:pagePath',
            [
                'metrics' => 'ga:pageviews,ga:uniquePageviews,ga:timeOnPage,ga:exits,ga:sessions',
                'dimensions' => 'ga:pagePath',
                'sort' => '-ga:pageviews'
            ]
        );
        $cls_data = $this->collectionRender($analyticsData);
        // var_dump($cls_data);
        // die();

        // thông kê tổng số truy cập và số xem trang theo ngay. 7 ngày
        $row5 = Analytics::fetchTotalVisitorsAndPageViews($period);
        
        $day_ary = [];
        $visitors_ary = [];
        $page_views_ary = [];
        foreach ($row5 as $value) {
            $date = $value["date"];
            $day_ary[] = $date->day . ' thg ' . $date->month;
            $page_views_ary[] = $value["pageViews"];
            $visitors_ary[] = $value["visitors"];
        }

        $sum['page_views'] = array_sum($page_views_ary);
        $sum['visitors'] = array_sum($visitors_ary);
        
        $chartjs = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 200])
        ->labels($day_ary)
        ->datasets([
            [
                "label" => "Số lần xem trang",
                'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => $page_views_ary,
            ],
            [
                "label" => "Người dùng",
                'backgroundColor' => "rgba(121, 186, 173, 0.7)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => $visitors_ary,
            ]
        ])
        ->options([]);

        return view('admin.analytic.index', compact('chartjs', 'cls_data'))->with($sum);
    }

    public function collectionRender($response = []){
        return collect($response['rows'] ?? [])->map(function (array $dateRow) {
            $seconds = (int) $dateRow[3];
            $hours = floor($seconds / 3600);
            $mins = floor($seconds / 60 % 60);
            $secs = floor($seconds % 60);
            return [
                // 'date' => Carbon::createFromFormat('Ymd', $dateRow[0]),
                'pagePath' => $dateRow[0],
                // 'pageTitle' => $dateRow[2],
                // 'visitors' => (int) $dateRow[2],
                'pageViews' => (int) $dateRow[1],
                'pageViewsUnique' => (int) $dateRow[2],
                'timeOnPage' => sprintf('%02d:%02d:%02d', $hours, $mins, $secs),
                'exits' => (int) $dateRow[4],
                'sessions' => (int) $dateRow[5],
                'rateExits' => $dateRow[5] != '0' ? number_format(100 / (int) $dateRow[5] * (int) $dateRow[4], 2) : '0.00' 
            ];
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
    }
}
