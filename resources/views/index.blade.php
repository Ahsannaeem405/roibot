@extends('layout.mainlayout')
@section('content')
    <!-- sidebar section -->
    <section>
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

                        <div class="card 1">
                            <div class="card_image"> <img src="images/gif/371907490_FACEBOOK_ICON_1080.gif" /> </div>
                            <div class="card_title title-white">
                                <a href="#" class="a_tag">
                                    Create Advertisement
                                </a>
                            </div>
                        </div>

                        <div class="card 2">
                            <div class="card_image">
                                <img src="images/gif/giphy.gif" />
                            </div>
                            <div class="card_title title-white">
                                <a href="#" class="a_tag">
                                    Create Advertisement
                                </a>
                            </div>
                        </div>



                    </div>


                    <div class="row m-3 pt-5 pb-5 rounded insight_row">
                        <div class="col-12 mb-5 text-center">
                            <h3 class="color">Insights</h3>
                        </div>
                        <div class="col-3">
                            <div class="bg-insight text-center">
                                <h5>Clicks</h5>
                                <p class="mb-0">5</p>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-insight text-center">
                                <h5>Impressions</h5>
                                <p class="mb-0">5</p>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-insight text-center">
                                <h5>CPC</h5>
                                <p class="mb-0">5</p>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-insight text-center">
                                <h5>Conversation</h5>
                                <p class="mb-0">5</p>
                            </div>
                        </div>
                    </div>

                    <div class="row m-3 mt-5 pt-5 pb-5 rounded insight_row">
                        <div class="col-12 mb-5 text-center">
                            <h3 class="color">Manage Ads</h3>
                        </div>
                        <div class="col-4">
                            <div class="bg-ads  pt-0 pb-0">
                                <a href="#">
                                    <img src="{{asset('images/ads.jpg')}}" class="img-fluid" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-ads pt-0 pb-0">
                                <a href="#">
                                    <img src="{{asset('images/ads.jpg')}}" class="img-fluid" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-ads pt-0 pb-0">
                                <a href="#">
                                    <img src="{{asset('images/ads.jpg')}}" class="img-fluid" alt="">
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    @endsection
    <!-- end sidebar section -->



