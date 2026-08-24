<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;

    protected $table = "personal_access_tokens"; 
    protected $connection = "mysql_master";    
}
