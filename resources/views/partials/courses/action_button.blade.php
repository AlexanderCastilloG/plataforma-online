<div class="col-2">
    @auth
      @can('opt_for_course', $course)
          {{-- Puede optar    --}}
          @can('subscribe', \App\Course::class)
            {{-- Se puede subscribir --}}
            <a class="btn btn-subscribe btn-block" href="{{ route('subscriptions.plans') }}">
                <i class="fa fa-bolt"></i> {{ __("Subscribirme") }}
            </a>
          @else 
            {{-- No se puede subscribir --}}
            @can('inscribe', $course)
                {{-- puede hacerlo --}}
                <a class="btn btn-subscribe btn-block" href="{{ route('courses.inscribe', ['slug'=> $course->slug ]) }}">
                    <i class="fa fa-bolt"></i> {{ __("Inscribirme") }}
                </a>
            @else 
                {{-- ya esta inscrito --}}
                <a class="btn btn-subscribe btn-block" href="">
                    <i class="fa fa-bolt"></i> {{ __("Inscrito") }}
                </a>
            @endcan
          @endcan

      @else 
        {{-- No puede --}}
        <a class="btn btn-subscribe btn-block" href="">
            <i class="fa fa-user"></i> {{ __("Soy autor") }}
        </a>
      @endcan
    @else
    {{-- No identificado --}}
    <a class="btn btn-subscribe btn-block" href="{{ route('login') }}">
        <i class="fa fa-user"></i> {{ __("Acceder") }}
    </a>
    @endauth
</div>