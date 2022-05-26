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

                                <div class="col-md-4 col-12 mt-3" style="height: fit-content;">
                                    <a href="{{url('manage_detail/'.$com->id.'')}}" class="a_card">

                                        <div class="box-shadow p-0 overflow-hidden h-100">



                                            <div class="position-relative">
                                                <span class="ml-2" >Ad . <span class="divurl_1"> {{isset($com->activeAdd[0]->url) ? $com->activeAdd[0]->url : null}} </span></span>


                                                <h5 class=" heading_fb  heading1_prev ml-2 mt-1" style="color: blue!important;">{{isset($com->activeAdd[0]->heading) ? $com->activeAdd[0]->heading : null}}</h5>

                                            </div>
                                            <div class="d-flex justify-content-between ">


                                                <p class="ml-2"> {{isset($com->activeAdd[0]->body) ? $com->activeAdd[0]->body : null}}</p>
                                            </div>

                                        </div>
                                    </a>
                                </div>


                            @endif


                        @endforeach

                        <div class="col-md-8 col-12 mt-2  text-center">
                            @if($com->step>=1 && $com->step<=4)

                                <div class="col-lg-12">
                                    <span class="text-danger text-align-text">under A/B testing</span>
                                </div>

                                @endif
                            @if(isset($com->activeAdd[0]->id))


                            <a href="{{url('insight_detail/'.$com->id.'/'.$com->activeAdd[0]->id.'')}}" class="btn theme-btn pl-5 pr-5 float-right">View Insight</a><br><br>
                                @endif
                                    <a href="{{url('compain/pause/'.$com->id.'')}}" onclick="return confirm('Are you sure you want to Pause this item?');">  <button class="btn btn-warning btn_manage mt-4 text-light">Pause</button></a><br>
                            <a href="{{url('compain/delete/'.$com->id.'')}}" onclick="return confirm('Are you sure you want to delete this item?');"><button class="btn btn-danger  mt-4 btn_manage text-light">Delete</button></a><br>
                            <a href="{{url('compain/reactive/'.$com->id.'')}}" onclick="return confirm('Are you sure you want to Reactivate this item?');">  <button class="btn btn-success  mt-4 btn_manage text-light">Reactivate</button></a><br>
                            <button class="btn btn-primary  mt-4 btn_manage text-light">Duplicate</button><br>


                            <button   data-toggle="modal" data-target="#publish{{$com->id}}"    @if($com->step!=5) disabled  @endif class="btn btn-secondary  mt-4 btn_manage text-light">
                                Publish ADD
                            </button><br>


                            @if($com->step==5)
                            <div class="modal fade" id="publish{{$com->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{url('publish/'.$com->id.'')}}" method="post">
                                            @csrf


                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">PUBLISH ADD</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="row">



                                                <div class="col-lg-12 text-left mb-3">
                                                    <lable>Per day budget</lable>
                                                    <input type="number" name="per_day" required class="form-control" value="{{$com->per_day}}">
                                                </div>

                                                <div class="col-lg-12 text-left mb-3">
                                                    <lable>Start date</lable>
                                                    <input type="datetime-local" name="start"  required class="form-control" value="{{ str_replace(' ','T',$com->start_date)}}">
                                                </div>
                                                <div class="col-lg-12 text-left mb-3">
                                                    <lable>End date</lable>
                                                    <input type="datetime-local" name="end" required class="form-control" value="{{ str_replace(' ','T',$com->end_date)}}">
                                                </div>

                                            </div>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">PUBLISH</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                                @endif


                        </div>


                    </div>

                </div>
            </div>
        </div><br>
    </section>
    <!-- end sidebar section -->



    @endsection
