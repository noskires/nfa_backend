<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Organization extends Model
{
    use HasFactory;
    protected $table = "lib_organizations";
}
