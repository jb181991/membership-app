<?php

namespace App\Imports;

use App\Customer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
   
class ImportCustomers implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $sales_rep = (Auth::user()->role_id == 5) ? Auth::user()->id : null;

        return new Customer([
            'first_name'    => $row['first_name'],
            'last_name'     => $row['last_name'],
            'city'          => $row['city'],
            'state'         => $row['state'],
            'company'       => $row['company'],
            'email'         => $row['email'],
            'customer_type' => $row['customer_type'],
            'user_id'       => Auth::user()->id,
            'sales_rep_id'  => $sales_rep
        ]);
    }
}

?>