<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zipcode extends Model
{
    use SoftDeletes;

    public $table = 'zipcodes';

    protected $casts = [
        '*' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'city',
        'state',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
