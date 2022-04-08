@extends('layout.mainlayout')





@section('content')
    <!-- sidebar section -->
    <section>
        <div class="container-fluid">
            <div class="row">

                <div class="col-12 pt-5">





                    <div class="row m-3 mt-2 pt-4 pb-5 rounded insight_row">

                        <div class="col-md-4 col-12 mt-2 text-center">
                            <div class="bg-ads  pt-0 pb-0">
                                <a href="#">
                                    <img src="./images/ads.jpg" class="img-fluid" alt="">
                                </a>
                            </div>
                            <h5 class="mt-4 color">Ad Heading</h5>
                        </div>

                        <div class="col-md-8 col-12 mt-2  text-center">
                            <button class="btn theme-btn pl-5 pr-5 float-right">View Insight</button><br><br>
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
