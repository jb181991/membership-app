<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = ['user_id', 'sales_rep_id', 'first_name', 'last_name', 'city', 'state', 'company', 'email', 'customer_type'];

    public function scopeCurrentUser($query)
    {
        return $query->where('sales_rep_id', Auth::user()->id);
    }

    public function scopeCustomView($query)
    {
        if(Auth::user()->role_id == 4)
        {
            $users = User::where('coach_id', Auth::user()->id)->pluck('id');

            $query->where('user_id', Auth::user()->id);
            
            foreach($users as $key => $user_id){
                $query->orWhere('sales_rep_id', $user_id);
            }

            return $query;
        } else if (Auth::user()->role_id == 5) {
            $query->where('sales_rep_id', Auth::user()->id);
            return $query;
        }
    }
}
