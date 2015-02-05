<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

	protected $fillable = [
        'description',
        'completed_at',
        'deleted_at'
    ];
}
