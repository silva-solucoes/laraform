<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormAvailability extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'form_id',
        'start_date',
        'end_date',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
