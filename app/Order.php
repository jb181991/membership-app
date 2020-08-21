<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    public function scopeTeamOrders($query)
    {
        if(Auth::user()->role_id == 4)
        {
            $users = User::where('coach_id', Auth::user()->id)->pluck('id');

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
