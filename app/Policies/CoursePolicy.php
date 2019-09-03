<?php

namespace App\Policies;

use App\User;
use App\Course;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Role;

class CoursePolicy
{
    use HandlesAuthorization;
    
    // Politica - Para que un profesor no se puede matricular a su propio curso 
    public function opt_for_course(User $user, Course $course) {
        return ! $user->teacher || $user->teacher->id !== $course->teacher_id;
    }

    //
    public function subscribe(User $user) {
        return $user->role_id !== Role::ADMIN && ! $user->subscribed('main');
    }

    public function inscribe(User $user, Course $course) {
        return ! $course->students->contains($user->student->id); // si el usuario no esta registrado
    }

    public function review(User $user, Course $course) {
        return ! $course->reviews->contains('user_id', $user->id); // Si el estudiante no a hecho una valoración
    }


}
