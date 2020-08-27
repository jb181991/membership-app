<?php

namespace App\Http\Controllers\Voyager;

use TCG\Voyager\Http\Controllers\VoyagerController as BaseVoyagerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Imports\ImportCustomers;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends BaseVoyagerController
{
    public function import() {
        Excel::import(new ImportCustomers, request()->file('csv_file'));
            
        return back();
    }
}
