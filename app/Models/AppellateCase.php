<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppellateCase extends Model
{
    use HasFactory;

    protected $table = 'appellate_cases';

    protected $fillable = [
        'case_no',
        'parties_name',
        'first_order',
        'last_order',
    ];
}
