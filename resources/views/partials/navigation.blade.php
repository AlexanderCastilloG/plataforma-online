<header>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>
            
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">

                    @include('partials.navigations.' . \App\User::navigation())

                    <li class="nav-item dropdown">
                        <a data-toggle="dropdown" id="navbarDropdownMenuLink" aria-haspopup="true"
                        aria-expanded="false" href="#" class="nav-link dropdown-toggle">
                        {{ __("Selecciona un idioma") }}
                        </a>
                        
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('set_language', ['es']) }}">
                                {{ __("Español") }}
                            </a>

                            <a class="dropdown-item" href="{{ route('set_language', ['en']) }}">
                                {{ __("Inglés") }}
                            </a>

                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>