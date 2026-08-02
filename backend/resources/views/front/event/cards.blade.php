@foreach ($places as $place)
    <div class="event-card">
        <a href="{{ url('event-details/' . ($place->slug ?? '')) }}">
            <figure class="snip1477">
                <img src="{{ asset($place->image ?? 'front_assets/imgs/popup_images/trip_1.jpg') }}"
                     alt="sample38" />
                <div class="title">
                    <div>
                        <h3>{!! $place->title ?  \Illuminate\Support\Str::limit($place->title, 40, $end='...') : '' !!}</h3>
                    </div>
                </div>
                <figcaption>
                    <p>{!! $place->description ?  \Illuminate\Support\Str::limit($place->description, 200, $end='...') : '' !!}</p>
                </figcaption>
            </figure>
        </a>
    </div>
@endforeach
