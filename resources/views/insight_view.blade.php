@extends('layout.mainlayout')
@section('content')
    <!-- sidebar section -->
    <section class="mb-5 section">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12 pt-5">
                    <div class="row m-3  pt-5 pb-5 rounded insight_row">
                        <div class="col-12 mb-5 text-center">
                            <h3 class="color">Insights</h3>
                        </div>
                        @foreach($compain as $com)

                            @if($com->type==1)
                                <div class="col-md-4 col-12 mt-3">
                                    <a href="{{url('insight_detail/'.$com->id.'/'.$com->activeAdd[0]->id.'')}}" class="a_card">

                                        <div class="box-shadow p-3">

                                            <div class="d-flex">
                                                <div>
                                                    <img src="{{asset('images/img_avatar.png')}}" class="rounded-circle" width="50" alt="">
                                                </div>
                                                <div class="ml-3">
                                                    <h5 class="mb-0">{{Auth::user()->name}}</h5>
                                                    <p class="gray mb-0">Sponsored <i class="fas fa-globe"></i></p>

                                                    <p class="text-justify">{{$com->activeAdd[0]->heading}} </p>
                                                </div>
                                            </div>
                                            <div class="pt-0 pb-0 text-center">

                                                <img src="{{asset('images/gallary/'.$com->activeAdd[0]->image.'')}}" class="img-fluid" alt="">

                                            </div>
                                            <div class="bg_gray d-flex p-2 justify-content-between">
                                                <div>
                                                    {{--                                    <h6 class="gray mb-0">Demo</h6>--}}
                                                    <p class="text-black-50">{{$com->activeAdd[0]->body}}</p>
                                                </div>
                                                <div class="my-auto">
                                                    <a href="{{$com->activeAdd[0]->url}}" target="_blank" class="btn btn-secondary learn">{{$com->activeAdd[0]->button}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            @else



                                <div class="col-md-4 col-12 mt-3">
                                    <a href="{{url('insight_detail/'.$com->id.'/'.$com->activeAdd[0]->id.'')}}" class="a_card">

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
    </section>
    <!-- end sidebar section -->



   @endsection
