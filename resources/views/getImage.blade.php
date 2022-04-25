@foreach($gallary as $gall)
    <div class="col-3 d-flex align-items-center my-2">
        <img src="{{asset('images/gallary/'.$gall->image.'') }}" img_name="{{$gall->image}}"
             class="w-100 add_img" alt="">
    </div>
@endforeach

