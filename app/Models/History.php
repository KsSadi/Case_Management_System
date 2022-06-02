<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected  $fillable = ['case_id','date','past_date','next_date','status'];

    public function cases()
    {
        return $this->hasOne(CaseItem::class,'id','case_id');

    }
}
