<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Course
 *
 * @property-read \App\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Goal[] $goals
 * @property-read \App\Level $level
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Requirement[] $requirements
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Student[] $students
 * @property-read \App\Teacher $teacher
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course query()
 * @mixin \Eloquent
 */
class Course extends Model
{
    //utilizar softDelete ->Para el borrado lógico
    use SoftDeletes;

    protected $fillable = ['teacher_id', 'name', 'description', 'picture', 'level_id', 'category_id', 'status'];
    //
    const PUBLISHED = 1;
    const PENDING = 2;
    const REJECTED = 3;

    // Para hacer el conteo, te devuelve las columnas de count_reviews , count_students de las relaciones
    protected $withCount = ['reviews', 'students'];

    //
    public static function boot() {
        parent::boot();

        //antes de que se haya guardado
        static::saving(function(Course $course) {
			if( ! \App::runningInConsole() ) {
				$course->slug = str_slug($course->name, "-");
			}
        });
        
        /**
         * update-> para actualizar
         * create-> para cuando se ha creado
         * saved-> para guardar o actualizar
         */
        static::saved(function(Course $course){
            //comprobar que no se este ejecutando en la consola
            if( ! \App::runningInConsole()){
                if( request('requirements')){
                    foreach (request('requirements') as $key => $requirement_input) {
                        if($requirement_input) {
                            // requirement_id -> es el campo hidden que le enviamos desde el formulario
                            Requirement::updateOrCreate(['id' => request('requirement_id'.$key)], [
                                'course_id' => $course->id,
                                'requirement' => $requirement_input
                            ]);
                        }
                    }
                }

                if( request('goals')){
                    foreach ( request('goals') as $key => $goal_input) {
                        if( $goal_input){
                            Goal::updateOrCreate(['id' => request('goal_id'.$key)], [
                                'course_id' => $course->id,
                                'goal' => $goal_input
                            ]);
                        }
                    }
                }
            }
        });
    }

    public function pathAttachment() {
        return "/images/courses/" . $this->picture;
    }

    // cambiar el id por el Url de la rutas o Slug, todo lo hace laravel internamente
    public function getRouteKeyName() {
        return 'slug';
    }

    public function category() {
        return $this->belongsTo(Category::class)->select('id', 'name');
    }

    public function goals() {
        return $this->hasMany(Goal::class)->select('id', 'course_id', 'goal');
    }

    public function level() {
        return $this->belongsTo(Level::class)->select('id', 'name');
    }

    public function reviews() {
        return $this->hasMany(Review::class)->select('id', 'user_id', 'course_id', 'rating', 'comment', 'created_at');
    }

    public function requirements() {
        return $this->hasMany(Requirement::class)->select('id', 'course_id', 'requirement');
    }

    // Relación de Muchos a Muchos
    public function students() {
        return $this->belongsToMany(Student::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    // Atributo personalizado - en Eloquent custom-rating
    public function getCustomRatingAttribute() {
        return $this->reviews->avg('rating'); // avg para el promedio
    }

    // Cursos Relacionados
    public function relatedCourses() {
        return Course::with('reviews')->whereCategoryId($this->category->id)
                                      ->where('id', '!=' , $this->id)
                                      ->latest()
                                      ->limit(6)
                                      ->get();
    }

}
