<?php

namespace Soufian212\LaraTransManager\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lang',
        'file',
        'key',
        'value',
    ];
}
