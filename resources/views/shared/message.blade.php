@foreach(['danger','warning','success','info'] as $message)
    @if(session()->has($message))
        <div class="flash-message">
            <p class="alert alert-{{$message}}">
                {{session()->get($message)}}
            </p>
        </div>
    @endif
@endforeach