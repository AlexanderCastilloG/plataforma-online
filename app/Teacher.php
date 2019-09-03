<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Teacher
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Course[] $courses
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Teacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Teacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Teacher query()
 * @mixin \Eloquent
 */
class Teacher extends Model
{
    protected $fillable = ['user_id']; //Para la asignación masiva

    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
