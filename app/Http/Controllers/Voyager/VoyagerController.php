<?php

namespace App\Http\Controllers\Voyager;

use TCG\Voyager\Http\Controllers\VoyagerController as BaseVoyagerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoyagerController extends BaseVoyagerController
{
    public function index()
    {
        $reports = new ReportsController();
        $years = $reports->getDatesFromRange(date('Y-m-01'), date('Y-m-d'));
        $customer_type = \App\Customer::selectRaw('DISTINCT(customer_type)')->get();

        $yrs = [];
        $yrs_cnt = [];
        $customerData = [];
        $customersLabel = [];
            
        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3) {
            
            foreach($customer_type as $row => $val) {
                $customerCnt = \App\Customer::where('customer_type', $val->customer_type)->count();
                
                array_push($customersLabel, $val->customer_type);
                array_push($customerData, $customerCnt == null ? 0 : $customerCnt);
            }

            foreach($years as $row) {
                $cnt = DB::table('orders')
                            ->join('customers', 'orders.customer_id', '=', 'customers.id')
                            ->whereDate('orders.created_at', $row)
                            ->count();

                array_push($yrs_cnt, $cnt);
                array_push($yrs, $row);
            }

            $data['customers_tbl'] = DB::table('customers')
                            ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                            ->selectRaw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name, users.name, customers.email, customers.customer_type')
                            ->orderBy('customers.created_at', 'DESC')
                            ->get();
            
            $data['orders_tbl'] = DB::table('orders')
                            ->join('customers', 'orders.customer_id', '=', 'customers.id')
                            ->select('*')
                            ->orderBy('orders.created_at', 'DESC')
                            ->get();

        } else if (Auth::user()->role_id == 4) {

            foreach($customer_type as $row => $val) {
                $customerCnt = DB::table('customers')
                            ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                            ->selectRaw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name, users.name, customers.email, customers.customer_type')
                            ->where('customer_type', $val->customer_type)
                            ->where('users.coach_id', Auth::user()->id)
                            ->count();
                
                array_push($customersLabel, $val->customer_type);
                array_push($customerData, $customerCnt == null ? 0 : $customerCnt);
            }

            foreach($years as $row) {
                $cnt = DB::table('orders')
                            ->join('customers', 'orders.customer_id', '=', 'customers.id')
                            ->join('users', 'orders.sales_rep_id', '=', 'users.id')
                            ->where('users.coach_id', Auth::user()->id)
                            ->whereDate('orders.created_at', $row)
                            ->count();

                array_push($yrs_cnt, $cnt);
                array_push($yrs, $row);
            }

            $data['customers_tbl'] = DB::table('customers')
                            ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                            ->selectRaw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name, users.name, customers.email, customers.customer_type')
                            ->where('users.coach_id', Auth::user()->id)
                            ->orderBy('customers.created_at', 'DESC')
                            ->get();
            
            $data['orders_tbl'] = DB::table('orders')
                            ->join('customers', 'orders.customer_id', '=', 'customers.id')
                            ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                            ->select('*')
                            ->where('users.coach_id', Auth::user()->id)
                            ->orderBy('orders.created_at', 'DESC')
                            ->get();

        } else if (Auth::user()->role_id == 5) {
            foreach($customer_type as $row => $val) {
                $customerCnt = DB::table('customers')
                            ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                            ->selectRaw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name, users.name, customers.email, customers.customer_type')
                            ->where('customer_type', $val->customer_type)
                            ->where('customers.sales_rep_id', Auth::user()->id)
                            ->count();
                
                array_push($customersLabel, $val->customer_type);
                array_push($customerData, $customerCnt == null ? 0 : $customerCnt);
            }

            foreach($years as $row) {
                $cnt = DB::table('orders')
                            ->join('customers', 'orders.customer_id', '=', 'customers.id')
                            ->join('users', 'orders.sales_rep_id', '=', 'users.id')
                            ->where('orders.sales_rep_id', Auth::user()->id)
                            ->whereDate('orders.created_at', $row)
                            ->count();

                array_push($yrs_cnt, $cnt);
                array_push($yrs, $row);
            }

            $data['customers_tbl'] = DB::table('customers')
                            ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                            ->selectRaw('CONCAT(customers.first_name, " ", customers.last_name) as customer_name, users.name, customers.email, customers.customer_type')
                            ->where('customers.sales_rep_id', Auth::user()->id)
                            ->orderBy('customers.created_at', 'DESC')
                            ->get();
            
            $data['orders_tbl'] = DB::table('orders')
                            ->join('customers', 'orders.customer_id', '=', 'customers.id')
                            ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                            ->select('*')
                            ->where('orders.sales_rep_id', Auth::user()->id)
                            ->orderBy('orders.created_at', 'DESC')
                            ->get();
        }

        $data['years'] = $yrs;
        $data['years_cnt'] = $yrs_cnt;
        $data['customers_label'] = $customersLabel;
        $data['customers_data'] = $customerData;
        
        $data['company_name'] = \App\User::selectRaw('DISTINCT(company)')->where('company', '!=', null)->pluck('company');

        return view('vendor.voyager.index', $data);
    }
}
