<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    function scopeFilter($query, $filter)
    {
        $query->when($filter['search']??false, function ($query, $search) {
            $query->where('username', 'LIKE', '%' . $search . '%')
            ->orwhere('email','LIKE', '%' . $search . '%');
        });
    }
}
