@extends('layout.mainlayout')
@section('content')
    <!-- sidebar section -->
    <section class="mb-5">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12 pt-5">
                    <div class="row m-3  pt-5 pb-5 rounded insight_row">
                        <div class="col-12 mb-5 text-center">
                            <h3 class="color">Insights</h3>
                        </div>
                        <div class="col-md-3 col-12 pt-2">
                            <div class="bg-ads  pt-0 pb-0">
                                <a href="{{url('insight_detail')}}">
                                    <img src="images/ads.jpg" class="img-fluid" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 pt-2">
                            <div class="bg-ads pt-0 pb-0">
                                <a href="{{url('insight_detail')}}">
                                    <img src="images/ads.jpg" class="img-fluid" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 pt-2">
                            <div class="bg-ads pt-0 pb-0">
                                <a href="{{url('insight_detail')}}">
                                    <img src="images/ads.jpg" class="img-fluid" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 pt-2">
                            <div class="bg-ads pt-0 pb-0">
                                <a href="{{url('insight_detail')}}">
                                    <img src="images/ads.jpg" class="img-fluid" alt="">
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- end sidebar section -->



   @endsection
