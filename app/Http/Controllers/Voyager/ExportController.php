<?php

namespace App\Http\Controllers\Voyager;

use TCG\Voyager\Http\Controllers\VoyagerController as BaseVoyagerController;
use PDF;
use App\User;
use App\Customer;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExportController extends BaseVoyagerController
{
     public function exportCustomers()
     {
        if(Auth::user()->role_id == 4) {
            $data['data'] = DB::table('customers')
                    ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                    ->selectRaw('customers.*, users.name')
                    ->where('users.coach_id', Auth::user()->id)
                    ->get();
        } else if (Auth::user()->role_id == 5) {
            $data['data'] = DB::table('customers')
                    ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                    ->selectRaw('customers.*, users.name')
                    ->where('customers.sales_rep_id', Auth::user()->id)
                    ->get();
        } else {
            $data['data'] = DB::table('customers')
                    ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                    ->selectRaw('customers.*, users.name')
                    ->get();
        }

        $pdf = PDF::loadView('pdf.customers', $data)->setPaper('a4', 'landscape');
        return $pdf->download('customer-list.pdf');
     }

     public function exportOrders()
     {
         if(Auth::user()->role_id == 4) {
            $data['data'] = DB::table('orders')
                    ->join('customers', 'orders.customer_id', '=', 'customers.id')
                    ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                    ->selectRaw('orders.*, users.name, CONCAT(customers.first_name, " ", customers.last_name) as customer')
                    ->where('users.coach_id', Auth::user()->id)
                    ->get();
        } else if (Auth::user()->role_id == 5) {
            $data['data'] = DB::table('orders')
                    ->join('customers', 'orders.customer_id', '=', 'customers.id')
                    ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                    ->selectRaw('orders.*, users.name, CONCAT(customers.first_name, " ", customers.last_name) as customer')
                    ->where('orders.sales_rep_id', Auth::user()->id)
                    ->get();
        } else {
            $data['data'] = DB::table('orders')
                    ->join('customers', 'orders.customer_id', '=', 'customers.id')
                    ->join('users', 'customers.sales_rep_id', '=', 'users.id')
                    ->selectRaw('orders.*, users.name, CONCAT(customers.first_name, " ", customers.last_name) as customer')
                    ->get();
        }

        $pdf = PDF::loadView('pdf.orders', $data)->setPaper('a4', 'landscape');
        return $pdf->download('order-list.pdf');
     }
}
