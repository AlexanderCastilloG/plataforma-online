<div class="col-12 pt-0 mt-0">
    <h2 class="text-muted">{{ __("Requisitos para tomas el curso") }}</h2>
    <hr>
</div>
@forelse ($requirements as $requirement)
    <div class="col-6">
        <div class="card bg-light p-3">
            {{ $requirement->requirement }}
        </div>
    </div>
@empty
    <div class="alert alert-dark">
        <i class="fa fa-info-circle"></i>
        {{ __("No hay ningún requisito para este curso")}}
    </div>
@endforelse