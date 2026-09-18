<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name','description', 'price','max_users','is_active'])]
class Plan extends Model
{
    public function getConnectionName()
    {
        return config('tenancy.database.central_connection');
    }
}
