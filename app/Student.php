<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Student
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Course[] $courses
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student query()
 * @mixin \Eloquent
 */
class Student extends Model
{
     // que datos se pueden insertar
     protected $fillable = ['user_id', 'title'];

     // $appends ->que lo podamos retornar cuando usamos el método get por ejemplo
     protected $appends = ['courses_formatted'];

    // Relación de muchos a muchos
    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    // Un Estudiante también es un usuario
    public function user() {
        return $this->belongsTo(User::class)->select('id', 'role_id', 'name', 'email');
    }

    // atributo personalizado
    public function getCoursesFormattedAttribute() {
        // pluck ->para definir las columnas que queremos devolver
        return $this->courses->pluck('name')->implode('<br/>');
    }
}
