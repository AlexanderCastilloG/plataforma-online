@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', [
        'title' => 'Configurar tu perfil',
        'icon' => 'user-circle'
    ])    
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __("Actualiza tus datos") }}
                    </div>

                    <div class="card-body">
                        <!-- novalidate => para que no lo valide el navegador -->
                        <form action="{{ route('profile.update') }}" method="post" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Correo electrónico") }}
                                </label>

                                <div class="col-md-6">
                                    <!-- readonly -> es para que no pueda editar -->
                                    <input type="email" id="email" name="email" readonly required autofocus
                                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    value="{{ old('email') ?: $user->email }}">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Contraseña") }}
                                </label>
        
                                <div class="col-md-6">
                                    <input type="password" id="password" name="password" required
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
        
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Corfirma la Contraseña") }}
                                </label>
            
                                <div class="col-md-6">
                                    <input type="password" id="password-confirm" name="password_confirmation" required
                                    class="form-control">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __("Actualizar datos") }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                @if ( !$user->teacher )
                    {{-- No es profesor --}}
                    <div class="card">
                        <div class="card-header">
                            {{ __("Convertirme en profesor de la plataforma") }}
                        </div>
                        <div class="card-body">
                            <form action="{{ route('solicitude.teacher') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fa fa-graduation-cap"></i> {{ __("Socilitar") }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- si lo es --}}
                    <div class="card">
                        <div class="card-header">
                            {{ __("Administrar los cursos que imparto") }}
                        </div>
                        <div class="card-body">
                            <a href="{{ route('teacher.courses') }}" class="btn btn-secondary btn-block">
                                <i class="fab fa-leanpub"></i> {{ __("Administrar cursos") }}
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            {{ __("Mis estudiantes") }}
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered text-nowrap" cellspacing="0" id="students-table">
                                <thead>
                                    <tr>
                                        <th>{{ __("ID") }}</th>
                                        <th>{{ __("Nombre") }}</th>
                                        <th>{{ __("Email") }}</th>
                                        <th>{{ __("Cursos") }}</th>
                                        <th>{{ __("Acciones") }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($user->socialAccount)
                    <div class="card">
                        <div class="card-header">
                            {{ __("Acceso con Socialite") }}
                        </div>
                        <div class="card-body">
                            <button class="btn btn-outline-dark btn-block">
                                {{ __("Registrado con") }}: <i class="fab fa-{{ $user->socialAccount->provider }}"></i>
                                {{ $user->socialAccount->provider }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('partials.modal')
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        let dt;
        let modal = jQuery("#appModal");
        
        jQuery(document).ready( function() {

            //Para que lo convierta en un objeto de dataTable
            dt = jQuery("#students-table").DataTable({
                pageLength: 5, //cuantos elementos queremos mostrar por página
                lengthMenu: [5, 10, 25, 50, 75, 100],
                processing: true, //Si queremos mostrar un mensaje mientra se esta procesando la información
                serverSide: true, //las peticiones lo va hacer frente al servidor

                ajax: '{{ route('teacher.students') }}',

                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                },

                columns: [
                    {data: 'user.id', visible: false}, // la propiedad visible-> sirve para ocultar o visualizar
                    {data: 'user.name'},
                    {data: 'user_id'},
                    {data: 'courses_formatted'},
                    {data: 'actions'}
                ]
            });

            jQuery(document).on("click", '.btnEmail', function(e){
                e.preventDefault();
                const id = jQuery(this).data('id');
                modal.find('.modal-title').text(' {{ __("Enviar mensaje") }}');
                modal.find('#modalAction').text(' {{ __("Enviar mensaje") }}').show();

                // se puede utilizar el $ o el jQuery
                let $form = $("<form id='studentMessage'></form>"); //crear un formulario dinámicamente
                $form.append(`<input type="hidden" name="user_id" value="${id}">`);
                $form.append(`<textarea class="form-control" name="message"></textarea>`);
                modal.find('.modal-body').html($form);

                modal.modal(); //mostrar el modal
            });

            jQuery(document).on("click", "#modalAction", function(e){
                jQuery.ajax({
                    url: '{{ route('teacher.send_message_to_student') }}',
                    type: 'POST',
                    headers: {
                        'x-csrf-token': $("meta[name=csrf-token]").attr('content') //extraer el contenido de layout.app
                    },
                    data: {
                        info: $("#studentMessage").serialize()
                    },
                    success: (res) => {
                        if( res.res){
                            modal.find('#modalAction').hide();
                            modal.find('.modal-body').html('<div class="alert alert-success">{{ __("Mensaje enviado correctamente") }}</div>');
                        }else {
                            modal.find('.modal-body').html('<div class="alert alert-danger">{{ __("Ha ocurrido un error enviando el correo") }}</div>');
                        }
                    }
                })
            })
        });

    </script>
@endpush