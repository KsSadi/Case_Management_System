<?php

namespace App\Models;

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseItem extends Model
{
    use HasFactory;

    protected  $fillable = ['case_items','case_no','division','project','case_type','court_name','parties_name','case_details','case_subject','first_order','adv_name'];



    public function projects()
    {
        return $this->hasOne(Project::class,'id','project');
    }
    public function divisions()
    {
        return $this->hasOne(Division::class,'id','division');
    }
    public function types()
    {
        return $this->hasOne(Type::class,'id','case_type');
    }
    public function courts()
    {
        return $this->hasOne(Court::class,'id','court_name');
    }
    public function advocates()
    {
        return $this->hasOne(Advocate::class,'id','adv_name');
    }

}
