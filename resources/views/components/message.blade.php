@if ($message = Session::get('message'))
    <div class="container alert alert-info alert-dismissible show text-center" role="alert">
        {{$message}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><bold>X</bold></span>
        </button>
    </div>
@endif
