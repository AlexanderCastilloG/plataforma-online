<?php

namespace App\Http\Controllers;

use App\VueTables\EloquentVueTables;
use App\Course;
use App\Mail\CourseApproved;
use App\Mail\CourseRejected;
use App\Student;
use Illuminate\Database\Eloquent\Builder;

// use Illuminate\Database\Query\Builder;

class AdminController extends Controller
{
    public function courses() {
        return view('admin.courses');
    }

    public function coursesJson() {
        //para comprobar si la petición es por ajax
        if(request()->ajax()){
            $vueTables = new EloquentVueTables;
            $data = $vueTables->get(new Course, ['id', 'name', 'status', 'slug'], ['reviews']);
            return response()->json($data);
        }
        return abort(401);
    }

    public function updateCourseStatus() {
        // courseId - status =>son enviados desde ajax a Vue
        // para comprobar si la petición es de tipo ajax
        if(\request()->ajax()){
            $course = Course::find(request('courseId'));

            // Aprobar el curso
            if( (int) $course->status !== Course::PUBLISHED &&  ! $course->previous_approved &&  \request('status') === Course::PUBLISHED){
                $course->previous_approved = true;
                \Mail::to($course->teacher->user)->send(new CourseApproved($course));
            }

            // Rechazar el curso
            if( (int) $course->status !== Course::REJECTED && ! $course->previous_rejected && \request('status') === Course::REJECTED) {
                $course->previous_rejected = true;
                \Mail::to($course->teacher->user)->send(new CourseRejected($course));
            }

            $course->status = \request('status');
            $course->save(); //guardar el curso
            return response()->json(['msg' => 'ok']);
        }

        return abort(401);
    }

    // Metódos que falta por hacer - son Tareas
    public function students() {
        return view('admin.students');
    }

    public function teachers() {
        return view('admin.teachers');
    }
}
