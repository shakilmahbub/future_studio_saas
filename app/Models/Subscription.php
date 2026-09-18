<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Tenant;
use App\Models\Plan;

#[Fillable(['tenant_id', 'plan_id', 'start_date', 'end_date', 'is_active', 'status'])]
class Subscription extends Model
{
    public function getConnectionName()
    {
        return config('tenancy.database.central_connection');
    }
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
