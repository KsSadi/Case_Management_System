<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighCourtCase extends Model
{
    use HasFactory;

    protected $table = 'high_court_cases';

    protected $fillable = [
        'case_no',
        'parties_name',
        'case_details',
        'first_order',
        'last_order',
    ];
}
