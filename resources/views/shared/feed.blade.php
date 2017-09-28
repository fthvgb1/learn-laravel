@if(count($feed_items))
    <ol class="statuses">
        @foreach($feed_items as $feed_item)
            @include('status.status',['user'=>$feed_item->user,'status'=>$feed_item])
        @endforeach
        {!! $feed_items->render() !!}
    </ol>
@endif