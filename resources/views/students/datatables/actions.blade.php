<a href="#" data-target="#appModal" title="{{ __("Enviar mensaje") }}" 
    data-id="{{ $user['id'] }}" class="btn btn-primary btnEmail">
    <i class="fa fa-envelope-square"></i>
</a>

{{--  data-id => la variables que puedes usar son $id, $user_id, ect de la tabla students
        tambien para hacer relación puedes utilizar $user ->relacion a User 
        para hacer relación a $courses _>relación a Courses
    --}}
 

{{-- data-id="{{ print_r($user) }}" --}}
