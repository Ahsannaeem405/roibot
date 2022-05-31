@extends('layout.mainlayout')
@section('content')
    <!-- sidebar section -->
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-12 sidebar p-0">
                    <ul class="list-unstyled pt-3  ul_sidebar">
                        <li class="p-3"><a href="{{url('/create_add')}}"><i class="fa fa-plus-circle"></i>&nbsp; Create Ad</a></li>
                        <li class="p-3"><a href="{{url('/manage_view')}}"><i class="fa fa-tasks"></i>&nbsp; Manage Ads</a></li>
                        <li class="p-3"><a href="{{url('/insight_view')}}"><i class="fa fa-lightbulb"></i>&nbsp; Insights</a></li>
                        <li class="p-3"><a href="{{url('/profile')}}"><i class="fa fa-user"></i>&nbsp; Profile</a></li>
                        <li class="p-3"><a href="{{url('/mediaGallery')}}"><i class="fa fa-images"></i>&nbsp; Manage Gallery</a></li>
                        {{-- <li class="p-3"><a href="{{url('/create_add')}}"><i class="fa fa-wallet"></i>&nbsp; Billing</a></li> --}}




                    </ul>
                </div>
                <div class="col-md-12 col-lg-10 col-12 pt-5">
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


                    <div class="row m-3 pt-5 pb-5 rounded insight_row">
                        <div class="col-12 mb-5 text-center">
                            <h3 class="color">Insights</h3>
                        </div>
                        <div class="col-md-3 col-12 mt-2">
                            <div class="bg-insight text-center">
                                <h5>Clicks</h5>
                                <p class="mb-0">{{$compain->sum('clicks')}}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2">
                            <div class="bg-insight text-center">
                                <h5>Impressions</h5>
                                <p class="mb-0">{{$compain->sum('impressions')}}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2">
                            <div class="bg-insight text-center">
                                <h5>CPC</h5>
                                <p class="mb-0">{{$compain->sum('cpc')}}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 mt-2">
                            <div class="bg-insight text-center">
                                <h5>Conversation</h5>
                                @php
                                  $val=$compain->sum('impressions')==0 ? 1 : $compain->sum('impressions');
                                @endphp
                                <p class="mb-0">{{$compain->sum('clicks')/$val}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row m-3 mt-5 pt-5 pb-5 rounded insight_row">
                        <div class="col-12 mb-5 text-center">
                            <h3 class="color">Manage Ads</h3>
                        </div>
                        @foreach($compain as $com)


                            @if($com->type==1)

                                <div class="col-md-4 col-12 mt-3">
                                    <a href="{{url('manage_detail/'.$com->id.'')}}" class="a_card">

                                        <div class="box-shadow p-3">
                                            <div class="d-flex">
                                                <div>
                                                    <img src="{{asset('images/img_avatar.png')}}" class="rounded-circle" width="50" alt="">
                                                </div>
                                                <div class="ml-3">
                                                    <h5 class="mb-0">{{Auth::user()->name}}</h5>
                                                    <p class="gray mb-0"> Sponsored <i class="fas fa-globe"></i></p>

                                                    <p class="text-justify">{{isset($com->activeAdd[0]->heading) ? $com->activeAdd[0]->heading : null}} </p>
                                                </div>
                                            </div>
                                            <div class="pt-0 pb-0 text-center">

                                                <img src="{{isset($com->activeAdd[0]->image) ? asset('images/gallary/'.$com->activeAdd[0]->image.'') : null}}" class="img-fluid" alt="">

                                            </div>
                                            <div class="bg_gray d-flex p-2 justify-content-between">
                                                <div>
                                                    {{--                                    <h6 class="gray mb-0">Demo</h6>--}}
                                                    <p class="text-black-50">{{isset($com->activeAdd[0]->body) ? $com->activeAdd[0]->body : null}}</p>
                                                </div>
                                                <div class="my-auto">
                                                    <a href="{{isset($com->activeAdd[0]->url) ? $com->activeAdd[0]->url : null}}" target="_blank" class="btn btn-secondary learn">{{isset($com->activeAdd[0]->button) ? $com->activeAdd[0]->button : null}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            @else



                                <div class="col-md-4 col-12 mt-3">
                                    <a href="{{url('manage_detail/'.$com->id.'')}}" class="a_card">

                                        <div class="box-shadow p-0 overflow-hidden  p-3  align-items-center">


                                            @if($com->goal=='SEARCH')

                                                <div class="position-relative">
                                                    <span class="ml-2">Ad . <span
                                                            class="divurl_1"> {{isset($com->activeAdd[0]->url) ? $com->activeAdd[0]->url : null}} </span></span>


                                                    <h5 class=" heading_fb  heading1_prev ml-2 mt-1"
                                                        style="color: blue!important;">{{isset($com->activeAdd[0]->heading) ? $com->activeAdd[0]->heading : null}}</h5>

                                                </div>
                                                <div class="d-flex justify-content-between ">


                                                    <p class="ml-2"> {{isset($com->activeAdd[0]->body) ? $com->activeAdd[0]->body : null}}</p>
                                                </div>

                                            @else
                                                <div class="position-relative">

                                                    <img
                                                        src="{{isset($com->activeAdd[0]->image) ? asset('images/gallary/'.$com->activeAdd[0]->image.'') : null}}"
                                                        class="img-fluid w-100 img1" alt="">

                                                </div>

                                            @endif

                                        </div>
                                    </a>
                                </div>

                            @endif


                        @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    @endsection
    <!-- end sidebar section -->



