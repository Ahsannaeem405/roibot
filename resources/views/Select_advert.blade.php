@extends('layout.mainlayout')
@section('content')
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center pt-5 pb-5">
                <h3>Select Advert Type</h3>
            </div>
        </div>
        <div class="cards-list">
            <a href="{{url('create_ad/1')}}" class="a_tag">
            <div class="card 1">
                {{-- <div class="card_image"> <img src="images/gif/371907490_FACEBOOK_ICON_1080.gif" /> </div> --}}
                <div class="card_image"> <img src="{{asset('images/fb.webp')}}" class="img-fluid" /> </div>

                <div class="card_title title-white">
                    <a href="{{url('create_ad/1')}}" class="a_tag">
                        Create Advert
                    </a>
                </div>
            </div>
            </a>

            <a href="{{url('create_ad/2')}}" class="a_tag">
            <div class="card 2">
                <div class="card_image">
                    {{-- <img src="images/gif/giphy.gif" /> --}}
                    <img src="{{asset('images/google.png')}}" class="img-fluid" />

                </div>
                <div class="card_title title-white">
                    <a href="{{url('create_ad/2')}}" class="a_tag">
                        Create Advert
                    </a>
                </div>
            </div>
            </a>



        </div>
    </div>
</section>

@endsection
