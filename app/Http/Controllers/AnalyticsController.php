<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Analytics;
use Spatie\Analytics\Period;
use Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use App\Article;

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
                'dimensions' => 'ga:pageTitle',
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
        $sum['articles'] = Article::where('status', Article::STATUS_ACTIVE)->count();
        
        $chartjs = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 190])
        ->labels($day_ary)
        ->datasets([
            [
                "label" => "Số lần xem trang",
                'backgroundColor' => "rgba(52, 159, 52, 0.31)",
                'borderColor' => "rgba(92, 184, 92, 0.7)",
                "pointBorderColor" => "rgba(92, 184, 92, 0.7)",
                "pointBackgroundColor" => "rgba(92, 184, 92, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => $page_views_ary,
            ],
            [
                "label" => "Người dùng",
                'backgroundColor' => "rgba(39, 99, 150, 1)",
                'borderColor' => "rgba(51, 122, 183, 0.7)",
                "pointBorderColor" => "rgba(51, 122, 183, 0.7)",
                "pointBackgroundColor" => "rgba(51, 122, 183, 0.7)",
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
            if ($dateRow[0] == '(not set)') {
                $dateRow[0] = 'Cô gái nặng 100 kg lấy lại vóc dáng thon thả nhờ nhảy dây mỗi ngày';
            }
                return [
                    // 'date' => Carbon::createFromFormat('Ymd', $dateRow[0]),
                    // 'pagePath' => $dateRow[0],
                    'pageTitle' => $dateRow[0],
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
