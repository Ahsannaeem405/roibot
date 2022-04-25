@extends('layout.mainlayout')
@section('content')
    <!-- sidebar section -->
    <section class="section">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12 pt-5">

                    <div class="row m-3 mt-2 pt-4 pb-5 rounded insight_row">

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
                                    <a href="{{url('manage_detail/'.$com->id.'')}}" class="a_card">

                                        <div class="box-shadow p-0 overflow-hidden h-100">



                                            <div class="position-relative text-center">

                                                <img src="{{asset('images/gallary/'.$com->activeAdd[0]->image.'')}}" class="img-fluid" alt="">

                                                <h4 class="position-absolute heading_fb text-white">{{$com->activeAdd[0]->heading}}</h4>

                                            </div>
                                            <div class="p-3 d-flex justify-content-between ">


                                                <p>{{$com->activeAdd[0]->body}}</p>
                                                <a href="{{$com->activeAdd[0]->url}}" target="_blank" class="my-auto"><i class="fas fa-angle-right font_icon "></i></a>
                                            </div>

                                        </div>
                                    </a>
                                </div>

                            @endif


                        @endforeach

                        <div class="col-md-8 col-12 mt-2  text-center">
                            <a href="{{url('insight_detail/'.$com->id.'/'.$com->activeAdd[0]->id.'')}}" class="btn theme-btn pl-5 pr-5 float-right">View Insight</a><br><br>
                            <a href="{{url('compain/pause/'.$com->id.'')}}" onclick="return confirm('Are you sure you want to Pause this item?');">  <button class="btn btn-warning btn_manage mt-4 text-light">Pause</button></a><br>
                            <a href="{{url('compain/delete/'.$com->id.'')}}" onclick="return confirm('Are you sure you want to delete this item?');"><button class="btn btn-danger  mt-4 btn_manage text-light">Delete</button></a><br>
                            <a href="{{url('compain/reactive/'.$com->id.'')}}" onclick="return confirm('Are you sure you want to Reactivate this item?');">  <button class="btn btn-success  mt-4 btn_manage text-light">Reactivate</button></a><br>
                            <button class="btn btn-primary  mt-4 btn_manage text-light">Duplicate</button><br>
                        </div>


                    </div>

                </div>
            </div>
        </div><br>
    </section>
    <!-- end sidebar section -->



    @endsection
