<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\Traits\UpdateReports;
use App\Models\SaleReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use UpdateReports;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->updateFeatureUsages(Auth::user());

        return view('dashboard.home', [
            'product_categories'    => $this->getProductCategories(),
            'charts'                => $this->getChartsData(),
            'statistics'            => $this->getStatisticsData()
        ]);
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

        return response()->json($this->monthlySalesData($month));
    }

    /**
     * Fetch all charts data
     *
     * @return mixed
     */
    private function getChartsData()
    {
        $this->updateSales();

        $charts['sales'] = $this->monthlySalesData();

        $charts['invoice'] = $this->invoiceDoughnutData();

        return $charts;
    }

    /**
     * Fetch all statistics data
     *
     * @return mixed
     */
    private function getStatisticsData()
    {
        $statistics['total_sales'] = $this->sumTotalSales();
        $statistics['products'] = $this->countProducts();
        $statistics['customers'] = $this->countCustomers();
        $statistics['weekly_sales'] = $this->weeklySalesData();

        return $statistics;
    }

    /**
     * Get this weeks data
     *
     * @return array
     */
    private function weeklySalesData()
    {
        $reports = Auth::user()->sale_reports()
            ->select(DB::raw('DAYOFWEEK(date) as day, SUM(total) as total'))
            ->where(DB::raw('WEEKOFYEAR(date)'), '=', DB::raw('WEEKOFYEAR(CURDATE())'))
            ->groupBy('day')->get();

        $data = [];

        for($i = 0; $i < 7; $i++){
            $reports->each(function ($record) use($i, &$data){
                $day = $record->day - 1;

                if($day == $i){
                    $data[$i] = $record->total;

                    return false;
                }
            });

            if(!isset($data[$i])){
                $data[$i] = 0;
            }
        }

        return $data;
    }

    /**
     * Get this months data
     *
     * @param null $month
     * @param null $year
     * @return array
     */
    private function monthlySalesData($month = null, $year = null)
    {
        $month = ($month)? $month : Carbon::now()->month;
        $year = ($year)? $year : Carbon::now()->year;

        $reports = Auth::user()->sale_reports()
            ->select(DB::raw('DAY(date) as day, SUM(total) as total'))->groupBy('day')
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
     * Get total sales sum
     *
     * @return mixed
     */
    private function sumTotalSales()
    {
        return Auth::user()->sale_reports()->sum('total');
    }

    /**
     * Get total customers count
     *
     * @return int
     */
    private function countCustomers()
    {
        return Auth::user()->customers()->count();
    }

    /**
     * Get total products count
     *
     * @return int
     */
    public function countProducts()
    {
        return Auth::user()->products()->count();
    }

    /**
     * Get invoice data
     *
     * @return \Illuminate\Support\Collection
     */
    private function invoiceDoughnutData()
    {
        $data = Auth::user()->customer_invoices()
            ->select(DB::raw('COUNT(*) as count, status'))
            ->groupBy('status')->get()->pluck('count', 'status');

        return $data;
    }

    public function getProductCategories()
    {
        return Auth::user()->product_categories->mapWithKeys(function ($record) {
            return [
                $record->id => [
                    'id'            => $record->id,
                    'products'      => $record->products()->count(),
                    'name'          => $record->name,
                ]
            ];
        })->toArray();
    }

    /**
     * @param User $user
     */
    private function updateFeatureUsages($user)
    {
        if($consumed = session()->get('consumed.features')){
            foreach($consumed as $key => $value){
                $user->record($key, $value);
            }

            session()->forget('consumed.features');
        }
    }
}
