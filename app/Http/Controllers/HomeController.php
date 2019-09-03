<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;

class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth')->except(['index']);
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /**
         * withCount ->permite el conteo de una relación y te crea una columna student_count
         * with -> para que traiga las informacion de todos los campos de las relaciones
         * latest -> para ordernarla del último elemento insertado
         * paginate -> para crear una paginacion de 12 en 12 
         */
        $courses = Course::withCount(['students'])
                    ->with('category', 'teacher', 'reviews')
                    ->where('status', Course::PUBLISHED)
                    ->latest()
                    ->paginate(12);

        return view('home', compact('courses'));
    }
}
