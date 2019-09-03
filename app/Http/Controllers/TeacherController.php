<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Database\Eloquent\Builder;
use App\User;
use App\Mail\MessageToStudent;
use App\Course;

class TeacherController extends Controller
{
    public function courses() {
      $courses = Course::withCount(['students'])->with('category', 'reviews')
          ->whereTeacherId(auth()->user()->teacher->id)->paginate(12);
      
      return view('teachers.courses', compact('courses'));
    }

    public function students() {
        //Obtener todos los estudiantes de un profesor
        $students = Student::with('user', 'courses.reviews')
                ->whereHas('courses', function(Builder $q){
                   $q->where('teacher_id', auth()->user()->teacher->id)->select(['id', 'teacher_id', 'name'])->withTrashed();
                })->get();

        $actions = 'students.datatables.actions'; //columna de action

        //addColumn ->datatable va comprobar si ese directorio existe sino lo va devolver el texto en plano
        // rawColumns ->para que nos respete como HTML
        return \DataTables::of($students)->addColumn('actions', $actions)->rawColumns(['actions', 'courses_formatted'])->make(true);
    }

    public function sendMessageToStudent() {
      
      // $info -> es el nombre de la variable que le estamos enviando desde el formulario de la data
      $info = \request('info');
      $data = [];
      parse_str($info, $data); //para parsiar la variable a un arreglo
      $user = User::findOrFail($data['user_id']); //Para encontrar el usuario

      try {
        \Mail::to($user)->send(new MessageToStudent( auth()->user()->name, $data['message']));
        $success = true;
      } catch (\Exception $exception) {
        $success = false;
      }

      return response()->json(['res' => $success]);
    }
}
