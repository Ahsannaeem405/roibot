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
                        <div class="col-md-3 col-6 mt-2">
                            <div class="bg-insight text-center">
                                <h5>Clicks</h5>
                                <p class="mb-0">5</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mt-2">
                            <div class="bg-insight text-center">
                                <h5>Impressions</h5>
                                <p class="mb-0">5</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mt-2">
                            <div class="bg-insight text-center">
                                <h5>CPC</h5>
                                <p class="mb-0">5</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mt-2">
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
                        <div class="col-md-4 col-12 mt-3">

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
                            <div class="bg_gray d-flex p-2 justify-content-between">
                                <div>
                                    <h6 class="gray mb-0">Demo</h6>
                                    <p class="text-black-50">Lorem Ipsum is simply dummy text</p>
                                </div>
                                <div class="my-auto">
                                    <a  href="{{url('manage_detail')}}" class="btn btn-secondary learn">Learn More</a>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-4 col-12 mt-3">

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
                            <div class="bg_gray d-flex p-2 justify-content-between">
                                <div>
                                    <h6 class="gray mb-0">Demo</h6>
                                    <p class="text-black-50">Lorem Ipsum is simply dummy text</p>
                                </div>
                                <div class="my-auto">
                                    <a href="{{url('manage_detail')}}" class="btn btn-secondary learn">Learn More</a>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-4 col-12 mt-3">

                            <div class="box-shadow p-0 overflow-hidden h-100">



                                <div class="position-relative">

                                        <img src="{{asset('images/ads.jpg')}}" class="img-fluid" alt="">

                                        <h4 class="position-absolute heading_fb">Helloo</h4>

                                </div>
                                <div class="p-3 d-flex justify-content-between ">


                                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                                <a href="#" class="my-auto"><i class="fas fa-angle-right font_icon "></i></a>
                            </div>

                            </div>
                        </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    @endsection
    <!-- end sidebar section -->



