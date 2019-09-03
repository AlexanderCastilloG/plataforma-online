<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Requirement
 *
 * @property-read \App\Course $course
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Requirement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Requirement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Requirement query()
 * @mixin \Eloquent
 */
class Requirement extends Model
{
    protected $fillable = ['course_id', 'requirement'];

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
