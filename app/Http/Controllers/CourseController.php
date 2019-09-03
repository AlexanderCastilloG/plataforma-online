<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mail\NewStudentInCourse;
use App\Review;
use App\Http\Requests\CourseRequest;
use App\Helpers\Helper;

class CourseController extends Controller
{
    public function show(Course $course) {

        /**
         * Cuando utilizamos el Model Binding , no podemos utilizar with para cargar la información
         * sino el método load
         */
        // load ->para cargar relaciones adicionales para este modelo
        $course->load([
            'category' => function($q) {
                $q->select('id', 'name');
            },
            'goals' => function($q) {
                $q->select('id', 'course_id', 'goal');
            },
            'level' => function($q) {
                $q->select('id', 'name');
            },
            'requirements' => function($q) {
                $q->select('id', 'course_id', 'requirement');
            },
            'reviews.user',
            'teacher'
        ])->get();

        // Curso Relacionado a esta categoria
        $related = $course->relatedCourses();

        return view('courses.detail', compact('course', 'related'));
    }

    public function inscribe(Course $course) {

        //Eso sirve: Para hacer un previu del mensaje 
        // return new NewStudentInCourse($course, "admin"); 

        //el usuario ya está inscrito
        $course->students()->attach(auth()->user()->student->id); //insertar un registro a una tabla pivot

        // Para enviar el mensaje a Mailtrap
        \Mail::to($course->teacher->user)->send(new NewStudentInCourse($course, auth()->user()->name));

        return back()->with('message', ['success', 'Inscrito correctamente al curso']);
    }

    //Devuelve todos los cursos que estan subscrito un usuario
    public function subscribed() {
        // Curso tiene estudiante
        $courses = Course::whereHas('students', function($query){
            $query->where('user_id', auth()->id());
        })->get(); //get para obtener todos

        return view('courses.subscribed', compact('courses'));
    }

    public function addReview() {

        Review::create([
            "user_id" => auth()->id(),
            "course_id" => request('course_id'),
            "rating" => (int) request('rating_input'),
            "comment" => request('message')
        ]);

        return back()->with('message', ['success', __('Muchas gracias por valorar el curso')]);
    }

    public function create() {
        $course = new Course;
        $btnText = __("Enviar curso para revisión");
        return view('courses.form', compact('course', 'btnText'));
    }

    public function store(CourseRequest $course_request) {
        
        $picture = Helper::uploadFile('picture', 'courses');
        $course_request->merge(['picture' => $picture]); //merge->para agregar una nueva entrada a la solicitud
        $course_request->merge(['teacher_id' => auth()->user()->teacher->id]);
        $course_request->merge(['status' => Course::PENDING]);

        Course::create($course_request->input()); //input-> van todos los entradas
        return back()->with('message', ['success', __("Curso enviado correctamente, recibirá un correo con cualquier información")]);
    }

    public function edit($slug) {
        $course = Course::with(['requirements', 'goals'])->withCount(['requirements', 'goals'])
            ->whereSlug($slug)->first();
        
        $btnText = __("Actualizar curso");
        return view('courses.form', compact('course', 'btnText'));
    }

    public function update(CourseRequest $course_request, Course $course) {

        if($course_request->hasFile('picture')) {
            \Storage::delete('courses/'. $course->picture); //eliminar la imagen del storage
            $picture = Helper::uploadFile("picture", 'courses'); //subir la imagen
            $course_request->merge(['picture' => $picture]);
        }

        //el metódo fill tenemos que pasarle todos los datos y el metódo save() para que persista en la DB
        $course->fill($course_request->input())->save(); //para actualizar todo el formulario
        return back()->with('message', ['success', __("Curso actualizado")]);
    }

    public function destroy(Course $course) {
        try {
            $course->delete();
            return back()->with('message', ['success', __("Curso eliminado correctamente")]);
        } catch (\Exception $exception) {
            return back()->with('message', ['danger', __("Error eliminando el curso")]);
        }
    }
}
