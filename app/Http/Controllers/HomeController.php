<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $data['day'] = 'NGÀY ' . $now->day;
        $data['date_time'] = ' THÁNG ' . $now->month . ' NĂM ' . $now->year;
        
        return view('app.index', $data);
    }
}
