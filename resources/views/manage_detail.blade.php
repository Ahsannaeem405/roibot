@extends('layout.mainlayout')





@section('content')
    <!-- sidebar section -->
    <section>
        <div class="container-fluid">
            <div class="row">

                <div class="col-12 pt-5">





                    <div class="row m-3 mt-2 pt-4 pb-5 rounded insight_row">

                        <div class="col-md-4 col-12 mt-2">
                            <div class="box-shadow p-3">

                                <div class="d-flex">
                                    <div>
                                    <img src="{{asset('images/img_avatar.png')}}" class="rounded-circle" width="50" alt="">
                                </div>
                                <div class="ml-3">
                                  <h5 class="mb-0">Name</h5>
                                  <p class="gray mb-0">Sponsored <i class="fas fa-globe"></i></p>
                                  <p class="text-justify">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. </p>
                                </div>
                                </div>
                                <div class="pt-0 pb-0">
                                    <a href="#">
                                        <img src="{{asset('images/ads.jpg')}}" class="img-fluid" alt="">
                                    </a>
                                </div>
                                <div class="bg_gray d-flex p-2 justify-content-center">
                                    <div>
                                        <h6 class="gray mb-0">Demo</h6>
                                        <p class="text-black-50">Lorem Ipsum is simply dummy text</p>
                                    </div>
                                    {{-- <div class="my-auto">
                                        <button class="btn btn-secondary learn">Learn More</button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 mt-2  text-center">
                            <a href="{{url('insight_detail')}}" class="btn theme-btn pl-5 pr-5 float-right">View Insight</a><br><br>
                            <button class="btn btn-warning btn_manage mt-4 text-light">Pause</button><br>
                            <button class="btn btn-danger  mt-4 btn_manage text-light">Delete</button><br>
                            <button class="btn btn-success  mt-4 btn_manage text-light">Reactivate</button><br>
                            <button class="btn btn-primary  mt-4 btn_manage text-light">Duplicate</button><br>
                        </div>


                    </div>

                </div>
            </div>
        </div><br>
    </section>
    <!-- end sidebar section -->



    @endsection
