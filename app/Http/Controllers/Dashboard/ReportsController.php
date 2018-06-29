<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\Traits\UpdateReports;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    use UpdateReports;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;
    
    public function index(Request $request)
    {
        $this->validate($request, [
            'from'            => 'nullable|date_format:Y-m-d H:i:s',
            'to'              => 'nullable|date_format:Y-m-d H:i:s|after:from',
        ]);

        $this->from = $request->from;
        $this->to = $request->to;

        return view('dashboard.reports.index', [
            'statistics'        => $this->getStatisticsData(),
            'charts'            => $this->getChartsData()
        ]);
    }

    /**
     * Fetch all statistics data
     *
     * @return mixed
     */
    private function getStatisticsData()
    {
        $statistics['total_sales'] = $this->sumTotal('total');
        $statistics['profit'] = $this->sumTotal('profit');
        $statistics['tax'] = $this->sumTotal('tax');
        $statistics['purchases'] = $this->sumTotal('purchases');

        return $statistics;
    }

    /**
     * Get line data for ajax request
     *
     * @param Request $request
     * @param $month
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxLineData(Request $request)
    {
        $month = $request->month ?: now()->month;

        return response()->json($this->monthlyProfitData($month));
    }


    /**
     * Fetch all charts data
     *
     */
    private function getChartsData()
    {
        $this->updateSales();

        $charts['profits'] = $this->monthlyProfitData();

        return $charts;

    }

    /**
     * Get this months data
     *
     * @param null $month
     * @param null $year
     * @return array
     */
    private function monthlyProfitData($month = null, $year = null)
    {
        $month = ($month)? $month : Carbon::now()->month;
        $year = ($year)? $year : Carbon::now()->year;

        $reports = Auth::user()->sale_reports()
            ->select(DB::raw('DAY(date) as day, SUM(profit) as total'))->groupBy('day')
            ->where(DB::raw('MONTH(date)'), '=', $month)->get();

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $labels = $data = [];

        for($i = 1; $i <= $days; $i++){
            $labels[] = $i;

            $reports->each(function ($record) use($i, &$data){
                if($record->day == $i){
                    $data[$i] = $record->total;

                    return false;
                }
            });

            if(!isset($data[$i])){
                $data[$i] = 0;
            }
        }

        $data = array_values($data);

        return compact('labels', 'data');
    }

    /**
     * Get total figures
     *
     * @return mixed
     */
    private function sumTotal($column)
    {
        $sales = Auth::user()->sale_reports();
        
        if($this->from){
            $sales = $sales->where('date', '>=', $this->from);
        }
        
        if($this->to){
            $sales = $sales->where('date', '<=', $this->to);
        }
        
        return $sales->sum($column);
    }

}
