<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'NroExp',
        'name',
        'last_name',
        'government_id',
        'state_id',
        'city_id',
        'farm',
        'account',
        'amount',
        'workflow_id',
        'certificate_pin'

    ];

    protected $connection = 'pgsql';


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $appends = ['resource_url'];
    protected $with = ['state', 'city', 'status', 'workflow'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/tasks/' . $this->getKey());
    }

    public function state()
    {
        return $this->hasOne(State::class, 'DptoId', 'state_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'CiuId', 'city_id');
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function status()
    {
        return $this->hasOne(ApplicationStatus::class)->latest('id');
    }
}
