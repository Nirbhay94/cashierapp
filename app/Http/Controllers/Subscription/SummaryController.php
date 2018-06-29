<?php

namespace App\Http\Controllers\Subscription;

use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Gerardojbaez\Laraplans\Models\Plan;
use Gerardojbaez\Laraplans\Models\PlanSubscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SummaryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('subscription.summary', [
            'statistics'        => $this->getStatisticsData(),
            'sales'             => $this->monthlySalesData(),
            'subscribed'        => $this->countSubscribed(),
            'non_subscribed'    => $this->countUnSubscribed()
        ]);
    }

    private function getStatisticsData()
    {
        $statistics['active'] = User::whereHas('subscriptions', function ($query) {
            $query->where('ends_at', '>', Carbon::now());
        })->count();
        $statistics['trial'] = User::whereHas('subscriptions', function ($query) {
            $query->where('ends_at', '<', Carbon::now())->where('trial_ends_at', '>', Carbon::now());
        })->count();
        $statistics['expired'] = User::whereHas('subscriptions', function ($query) {
            $query->where('ends_at', '<', Carbon::now())->where('trial_ends_at', '<', Carbon::now());
        })->count();
        $statistics['total_income'] = Invoice::sum('total');

        return $statistics;
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

        $reports = Invoice::select(DB::raw('DAY(created_at) as day, SUM(total) as total'))->groupBy('day')
            ->where(DB::raw('MONTH(created_at)'), '=', $month)->get();

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

    public function countSubscribed()
    {
        return User::wherehas('subscriptions')->count();
    }

    public function countUnSubscribed()
    {
        return User::doesntHave('subscriptions')->count();
    }
}
