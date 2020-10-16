<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name', 'mobile', 'address'
    ];

    /**
     * Attributes of User which are to be checked while searching.
     *
     * @var array
     */
    public static $searchableAttributes = [
        'email', 'name', 'mobile', 'address'
    ];

    /**
     * Attributes of User through which Users are to be sorted.
     *
     * @var array
     */
    public static $sortableAttributes = [
        'id', 'email', 'name', 'mobile', 'address'
    ];
}
