@extends('layout.mainlayout')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
<link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>

<style>
    * {
        box-sizing: border-box;
    }

    .add_img {
        cursor: pointer;
    }

    body {
        background-color: #f1f1f1;
    }

    #regForm {
        background-color: #ffffff;
        margin: 60px auto;
        padding: 40px;
        /* width: 70%; */
        min-width: 300px;
        min-height: 410px;
        border-radius: 8px;
        border: 1px solid #000;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }

    .preview1 {
        margin: 60px auto;
        border-radius: 15px;

        border: 1px solid #000;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }

    .preview {
        margin: 60px auto;
        border-radius: 8px;

        border: 1px solid #000;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }

    #regForm p {
        font-size: 16px;
        margin-bottom: 4px;
    }

    h1 {
        text-align: center;
    }
    .select2-container{
        width: 100% !important;
    }

    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
        border-radius: 10px;
    }

    /* Mark input boxes that gets an error on validation: */
    .invalid {
        background-color: #ffdddd !important;

    }

    /* Hide all steps by default: */
    .tab {
        display: none;
    }

    button {
        background-color: #1E3263;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 17px;
        cursor: pointer;
        border-radius: 8px;
        border: none;
    }

    button:focus {
        border: none;
        outline: none;
        box-shadow: none;
    }

    button:hover {
        opacity: 0.8;
    }

    #prevBtn {
        background-color: #bbbbbb;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    .step.active {
        opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #1E3263;
    }

    .step-heading {
        background-color: #1E3263;
        color: white;
        width: fit-content;
        padding: 5px 10px;
        border-radius: 8px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    }

    .form-control:focus {
        outline: none;
        box-shadow: none;
    }

    input:focus {
        box-shadow: inset 2px 2px 2px rgba(0, 0, 0, 0.3);
    }

    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        background-color: blue !important;
    }

    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link {
        background-color: #c4c9d9 !important;
    }

    .card {
        width: auto !important;
        box-shadow: none !important;
        border-radius: 0px !important;
        margin: 15px auto !important;

    }
    .card:hover{
        transform: none !important;
    }

    .btn {
        box-shadow: none !important;
    }
    .slider-box {width: 100%; margin: 25px auto}


    .slider {margin: 25px 0}

</style>
@section('content')

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Image</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{url('upload/image')}}" enctype="multipart/form-data" class="dropzone dropzone-area"
                          id="dpz-remove-thumb">
                        @csrf
                        <div class="dz-message">Drop Files Here To Upload</div>
                    </form>

                    <div class="images-div row w-100" style="height: 250px;overflow: auto">

                        @foreach($gallary as $gall)
                            <div class="col-3 d-flex align-items-center my-2">
                                <img src="{{asset('images/gallary/'.$gall->image.'') }}" img_name="{{$gall->image}}"
                                     class="w-100 add_img" alt="">
                            </div>
                        @endforeach

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onClick="refreshPage()"
                            data-dismiss="modal">Close
                    </button>

                </div>
            </div>
        </div>
    </div>


    <section class="section">
        <div class="container-fluid">


            <div class="mt-5">
                <h1>Create Your Add:</h1>
            </div>
            <div class="row">
                <div class="col-md-8 col-12 mt-2">
                    <form id="regForm" method="post" action="{{'/post/add'}}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="advert_type" class="text" value="{{$advert}}">
                        <!-- One "tab" for each step in the form: -->

                        <div class="tab">
                            <h3 class="text-center step-heading mx-auto">Step: 1</h3>




                            <p class="my-3">
                                Campaign Title
                                <input type="text" class="my-2" name="title" placeholder="Campaign Title">
                            </p>


                            <p class="my-3">

                            <div class="slider-box">
                                <label style="border: none" for="priceRange">Audience:</label>
                                <input type="text" name="age" style="border: none !important;text-align: center;font-size: 30px" id="priceRange" readonly>
                                <div id="price-range"  class="slider"></div>
                            </div>



                            <select class="form-control mt-2" name="gender">

                                <option selected value="1">Male</option>
                                <option value="2">Female</option>
                                <option value="0">Both</option>
                            </select>
                            </p>

                            <p class="my-3">
                                Budget:

                                <input placeholder="Per Day" name="perday_budget"
                                       class="mt-2 perday_budget" type="number">

                            </p>

                            <p class="my-3">
                                Start Date:

                                <input name="start_date"
                                       class="mt-2 " type="datetime-local">

                            </p>

                            <p class="my-3">
                                End Date:

                                <input name="end_date"
                                       class="mt-2 " type="datetime-local">

                            </p>



                        </div>
                        <div class="tab mt-4">
                            <h3 class="text-center step-heading mx-auto">Step: 2</h3>
                            <p>
                                Select Advertisement Goals:
                                @if($advert==1)
                                    <select name="goal" class="form-control mt-2">
                                        <option selected value="">Choose Goal ...</option>
                                        <option value="APP_INSTALLS">APP INSTALLS</option>
                                        <option value="BRAND_AWARENESS">BRAND AWARENESS</option>
                                        <option value="EVENT_RESPONSES">EVENT RESPONSES</option>
                                        <option value="LEAD_GENERATION">LEAD GENERATION</option>
                                        <option value="LINK_CLICKS">LINK CLICKS</option>
                                        <option value="LOCAL_AWARENESS">LOCAL AWARENESS</option>
                                        <option value="MESSAGES">MESSAGES</option>
                                        <option value="OFFER_CLAIMS">OFFER CLAIMS</option>
                                        <option value="PAGE_LIKES">PAGE LIKES</option>
                                        <option value="POST_ENGAGEMENT">POST ENGAGEMENT</option>
                                        <option value="PRODUCT_CATALOG_SALES">PRODUCT CATALOG SALES</option>
                                        <option value="REACH">REACH</option>
                                        <option value="STORE_VISITS">STORE VISITS</option>
                                        <option value="VIDEO_VIEWS">VIDEO VIEWS</option>
                                    </select>
                                @else

                                    <select name="goal" class="form-control mt-2">
                                        <option selected value="">Choose Goal ...</option>
                                        <option>Views</option>
                                        <option>Clicks</option>
                                        <option>Traffic</option>
                                        <option>Orders</option>
                                    </select>

                                @endif
                            </p>


                            <p class="mt-3 w-100">
                                Geo Location Country:
                                <select class="form-control  mt-2 js-example-basic-multiple w-100" name="countries[]"
                                        multiple="multiple">
                                    @foreach($country->data as $con)
                                        <option value="{{$con->country_code}}">{{$con->name}}</option>
                                    @endforeach
                                </select>
                            </p>

                            <p class="mt-3 w-100">
                                Geo Location City:
                                <select id="search_city" class="form-control mt-2 js-example-basic-multiple city"
                                        name="city[]" multiple="multiple">
                                    @foreach($city->data as $con)
                                        <option value="{{$con->key}},{{$con->name}}">{{$con->name}}</option>
                                    @endforeach

                                </select>
                            </p>

                            <p class="mt-3">
                                Radius <span id="radius">10 miles</span>:
                                <input type="range" id="change_radius" name="radius" value="10">
                            </p>




                        </div>

                        <div class="tab mt-4">
                            <h3 class="text-center step-heading mx-auto">Step: 3</h3>


                            <div class="col-lg-12 mt-5">

                                <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#Demographics"
                                           role="tab" aria-controls="home" aria-selected="true">Demographics</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#Interests"
                                           role="tab" aria-controls="profile" aria-selected="false">Interests</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#Behaviours"
                                           role="tab" aria-controls="contact" aria-selected="false">Behaviours</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="Demographics" role="tabpanel"
                                         aria-labelledby="home-tab">

                                        <div id="accordion">
                                            @foreach($demographics as $demo)
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between" id="headingThree{{$demo->id}}">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseThree{{$demo->id}}"
                                                                    aria-expanded="true"
                                                                    aria-controls="collapseThree{{$demo->id}}">
                                                                {{$demo->name}}
                                                            </button>
                                                        </h5>

                                                        <span><i    data-toggle="collapse"
                                                                    data-target="#collapseThree{{$demo->id}}"
                                                                    aria-expanded="true"
                                                                    aria-controls="collapseThree{{$demo->id}}" class="fa fa-caret-down mt-3"></i></span>


                                                    </div>

                                                    <div id="collapseThree{{$demo->id}}" class="collapse"
                                                         aria-labelledby="headingThree{{$demo->id}}"
                                                         data-parent="#accordion">
                                                        <div class="card-body">


                                                            @foreach($demo->child as $child)

                                                                <p><input name="{{$child->type}}[]" value="{{$child->data_id}},{{$child->name}}" type="checkbox" style="width: auto" class="mr-2">{{$child->name}}</p>




                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>


                                    </div>
                                    <div class="tab-pane fade" id="Interests" role="tabpanel"
                                         aria-labelledby="profile-tab">


                                        <div id="accordion">
                                            @foreach($intrests as $demo)
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between" id="headingOne{{$demo->id}}">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseOne{{$demo->id}}"
                                                                    aria-expanded="true"
                                                                    aria-controls="collapseOne{{$demo->id}}">
                                                                {{$demo->name}}
                                                            </button>
                                                        </h5>


                                                        <span><i   data-toggle="collapse"
                                                                   data-target="#collapseOne{{$demo->id}}"
                                                                   aria-expanded="true"
                                                                   aria-controls="collapseOne{{$demo->id}}" class="fa fa-caret-down mt-3"></i></span>



                                                    </div>

                                                    <div id="collapseOne{{$demo->id}}" class="collapse"
                                                         aria-labelledby="headingOne{{$demo->id}}"
                                                         data-parent="#accordion">
                                                        <div class="card-body">


                                                            @foreach($demo->child as $child)

                                                                @if(count($child->child)==0)

                                                                    <p><input name="interest[]" value="{{$child->data_id}},{{$child->name}}" type="checkbox" style="width: auto" class="mr-2">{{$child->name}}</p>

                                                                @else
                                                                    <div id="accordion">

                                                                        <div class="card">
                                                                            <div class="card-header d-flex justify-content-between" id="headingOne{{$child->id}}">
                                                                                <h5 class="mb-0">
                                                                                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne{{$child->id}}" aria-expanded="false" aria-controls="collapseOne{{$child->id}}">
                                                                                        {{$child->name}}
                                                                                    </button>
                                                                                </h5>

                                                                                <span><i    data-toggle="collapse" data-target="#collapseOne{{$child->id}}" aria-expanded="false" aria-controls="collapseOne{{$child->id}}" class="fa fa-caret-down mt-3"></i></span>
                                                                            </div>
                                                                            <div id="collapseOne{{$child->id}}" class="collapse" aria-labelledby="headingOne{{$child->id}}" data-parent="#accordion">
                                                                                <div class="card-body">
                                                                                    @foreach($child->child as $childf)
                                                                                        <div><input name="interest[]" value="{{$childf->data_id}},{{$childf->name}}" type="checkbox" style="width: auto" class="mr-2">{{$childf->name}}</div>
                                                                                    @endforeach


                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                    </div>


                                                                @endif
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>


                                    </div>
                                    <div class="tab-pane fade" id="Behaviours" role="tabpanel"
                                         aria-labelledby="contact-tab">
                                        <div id="accordion">
                                            @foreach($behaviour as $demo)
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between" id="headingTwo{{$demo->id}}">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#collapseTwo{{$demo->id}}"
                                                                    aria-expanded="true"
                                                                    aria-controls="collapseTwo{{$demo->id}}">
                                                                {{$demo->name}}
                                                            </button>
                                                        </h5>

                                                        <span><i     data-toggle="collapse"
                                                                     data-target="#collapseTwo{{$demo->id}}"
                                                                     aria-expanded="true"
                                                                     aria-controls="collapseTwo{{$demo->id}}" class="fa fa-caret-down mt-3"></i></span>


                                                    </div>

                                                    <div id="collapseTwo{{$demo->id}}" class="collapse"
                                                         aria-labelledby="headingTwo{{$demo->id}}"
                                                         data-parent="#accordion">
                                                        <div class="card-body">


                                                            @foreach($demo->child as $child)

                                                                @if(count($child->child)==0)

                                                                    <p><input name="behaviour[]" value="{{$child->data_id}},{{$child->name}}" type="checkbox" style="width: auto" class="mr-2">{{$child->name}}</p>

                                                                @else
                                                                    <div id="accordion">

                                                                        <div class="card">
                                                                            <div class="card-header d-flex justify-content-between" id="headingTwo{{$child->id}}">
                                                                                <h5 class="mb-0">
                                                                                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo{{$child->id}}" aria-expanded="false" aria-controls="collapseTwo{{$child->id}}">
                                                                                        {{$child->name}}
                                                                                    </button>
                                                                                </h5>


                                                                                <span><i     data-toggle="collapse" data-target="#collapseTwo{{$child->id}}" aria-expanded="false" aria-controls="collapseTwo{{$child->id}}" class="fa fa-caret-down mt-3"></i></span>


                                                                            </div>
                                                                            <div id="collapseTwo{{$child->id}}" class="collapse" aria-labelledby="headingTwo{{$child->id}}" data-parent="#accordion">
                                                                                <div class="card-body">
                                                                                    @foreach($child->child as $childf)
                                                                                        <div><input name="behaviour[]" value="{{$childf->data_id}},{{$childf->name}}" type="checkbox" style="width: auto" class="mr-2">{{$childf->name}}</div>
                                                                                    @endforeach


                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                    </div>


                                                                @endif
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>

                                    </div>
                                </div>

                            </div>








                        </div>

                        <div class="tab">
                            <h3 class="text-center step-heading mx-auto">Step: 4</h3>

                            <p class="my-3">
                            <div class="d-flex justify-content-between align-items-center my-2">
                                Add Heading
                                <button class="heading-btn" type="button">+</button>
                            </div>
                            <div class="heading-feilds">
                                <input placeholder="Heading 1" class="preview_1 heading1_btn"
                                       value="This is your heading" name="heading[]">
                            </div>
                            </p>

                            <p class="my-3">
                            <div class="d-flex justify-content-between align-items-center my-2">
                                Add Body Text
                                <button class="add-text" type="button">+</button>
                            </div>
                            <div class="text-feilds">
                                <input placeholder="Text 1" value="This is your body" name="body[]"
                                       class="preview_1 body1_btn">
                            </div>
                            </p>


                            <p class="my-3">
                            <div class="d-flex justify-content-between align-items-center my-2">
                                Action button:
                                <button class="add-button" value="Action" type="button">+</button>
                            </div>
                            <div class="button-feilds">

                                <select name="btn[]" id="" class="preview_1 form-control text1_btn mb-2">
                                    <option value="BOOK_TRAVEL" selected>BOOK TRAVEL</option>
                                    <option value="CONTACT_US">CONTACT US</option>
                                    <option value="DONATE">DONATE</option>
                                    <option value="DONATE_NOW">DONATE NOW</option>
                                    <option value="DOWNLOAD">DOWNLOAD</option>
                                    <option value="GET_DIRECTIONS">GET DIRECTIONS</option>
                                    <option value="GO_LIVE">GO LIVE</option>
                                    <option value="INTERESTED">INTERESTED</option>
                                    <option value="LEARN_MORE">LEARN MORE</option>
                                    <option value="LIKE_PAGE">LIKE PAGE</option>
                                    <option value="MESSAGE_PAGE">MESSAGE PAGE</option>
                                    <option value="SAVE">SAVE</option>
                                    <option value="SEND_TIP">SEND TIP</option>
                                    <option value="SHOP_NOW">SHOP NOW</option>
                                    <option value="SIGN_UP">SIGN UP</option>
                                    <option value="VIEW_INSTAGRAM_PROFILE">VIEW INSTAGRAM PROFILE</option>
                                    <option value="INSTAGRAM_MESSAGE">INSTAGRAM MESSAGE</option>
                                    <option value="LOYALTY_LEARN_MORE">LOYALTY LEARN MORE</option>
                                    <option value="PURCHASE_GIFT_CARDS">PURCHASE GIFT CARDS</option>
                                    <option value="PAY_TO_ACCESS">PAY TO ACCESS</option>
                                    <option value="SEE_MORE">SEE MORE</option>
                                    <option value="TRY_IN_CAMERA">TRY IN CAMERA</option>
                                    <option value="WHATSAPP_LINK">WHATSAPP LINK</option>
                                    <option value="GET_MOBILE_APP">GET MOBILE APP</option>
                                    <option value="INSTALL_MOBILE_APP">INSTALL MOBILE APP</option>
                                    <option value="USE_MOBILE_APP">USE_MOBILE APP</option>
                                    <option value="INSTALL_APP">INSTALL APP</option>
                                    <option value="USE_APP">USE APP</option>
                                    <option value="PLAY_GAME">PLAY GAME</option>
                                    <option value="WATCH_VIDEO">WATCH VIDEO</option>
                                    <option value="WATCH_MORE">WATCH MORE</option>
                                    <option value="OPEN_LINK">OPEN LINK</option>
                                    <option value="NO_BUTTON">NO BUTTON</option>
                                    <option value="LISTEN_MUSIC">LISTEN MUSIC</option>
                                    <option value="MOBILE_DOWNLOAD">MOBILE DOWNLOAD</option>
                                    <option value="GET_OFFER">GET OFFER</option>
                                    <option value="GET_OFFER_VIEW">GET OFFER VIEW</option>
                                    <option value="BUY_NOW">BUY NOW</option>
                                    <option value="BUY_TICKETS">BUY TICKETS</option>
                                    <option value="UPDATE_APP">UPDATE APP</option>
                                    <option value="BET_NOW">BET NOW</option>
                                    <option value="ADD_TO_CART">ADD TO CART</option>
                                    <option value="ORDER_NOW">ORDER NOW</option>
                                    <option value="SELL_NOW">SELL NOW</option>
                                    <option value="GET_SHOWTIMES">GET SHOWTIMES</option>
                                    <option value="LISTEN_NOW">LISTEN NOW</option>
                                    <option value="GET_EVENT_TICKETS">GET EVENT TICKETS</option>
                                    <option value="SEARCH_MORE">SEARCH MORE</option>
                                    <option value="PRE_REGISTER">PRE REGISTER</option>
                                    <option value="SWIPE_UP_PRODUCT">SWIPE UP PRODUCT</option>
                                    <option value="SWIPE_UP_SHOP">SWIPE UP SHOP</option>
                                    <option value="PLAY_GAME_ON_FACEBOOK">PLAY GAME ON FACEBOOK</option>
                                    <option value="CALL">CALL</option>
                                    <option value="MISSED_CALL">MISSED CALL</option>
                                    <option value="CALL_NOW">CALL NOW</option>
                                    <option value="APPLY_NOW">APPLY NOW</option>
                                    <option value="TRY_IT">TRY IT</option>

                                </select>
                                {{--                                <input placeholder="Button 1" value="Action" class="preview_1 text1_btn mb-1"--}}
                                {{--                                       name="btn[]">--}}

                                Action button url:
                                <input class="mt-2" placeholder="https://example.com" name="url[]">

                            </div>
                            </p>

                            <p class="my-3">
                            <div class="d-flex justify-content-between align-items-center my-2">
                                Add Image
                                <button class="image-btn" type="button">+</button>
                            </div>

                            <div class="img-feilds row">


                            </div>
                            </p>
                        </div>
                        {{--                        <div class="tab">--}}
                        {{--                            <h3 class="text-center step-heading mx-auto">Step: 4</h3>--}}
                        {{--                        </div>--}}
                        <div style="overflow:auto;" class="mt-4">
                            <div style="float:right;">
                                <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                            </div>
                        </div>
                        <!-- Circles which indicates the steps of the form: -->
                        <div style="text-align:center;margin-top:40px;">
                            <span class="step"></span>
                            <span class="step"></span>
                            <span class="step"></span>
                            <span class="step"></span>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-12 mt-2">
                    <div class="preview1">
                        @if ($advert==1)
                            <a class="a_card">

                                <div class="box-shadow p-3">

                                    <div class="d-flex">
                                        <div>
                                            <img src="{{asset('images/img_avatar.png')}}" class="rounded-circle"
                                                 width="50" alt="">
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="mb-0">{{Auth::user()->name}}</h5>
                                            <p class="gray mb-0"> Sponsored <i class="fas fa-globe"></i></p>
                                            <p class="text-justify heading1_prev">This is your heading.</p>
                                        </div>
                                    </div>
                                    <div class="pt-0 pb-0 text-center">
                                        <a>
                                            <img src="{{asset('images/adsdata.jpg')}}" class="img-fluid w-100 img1" alt="">

                                        </a>
                                    </div>
                                    <div class="bg_gray d-flex p-2 justify-content-between">
                                        <div>
                                            {{--                                    <h6 class="gray mb-0">Demo</h6>--}}
                                            <p class="text-black-50 body1_prev">This is your body.</p>
                                        </div>
                                        <div class="my-auto">
                                            <button class="btn btn-secondary learn action1_prev">Action</button>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @else
                            <a class="a_card">

                                <div class="box-shadow p-0 overflow-hidden">


                                    <div class="position-relative text-center">

                                        <img src="{{asset('images/adsdata.jpg')}}" class="img-fluid w-100 img1" alt="">

                                        <h4 class="position-absolute heading_fb text-white heading1_prev">This is your
                                            heading</h4>

                                    </div>
                                    <div class="p-3 d-flex justify-content-between ">


                                        <p class="body1_prev">This is your body</p>
                                        <a class="my-auto"><i class="fas fa-angle-right font_icon "></i></a>
                                    </div>

                                </div>
                            </a>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    </section>

@endsection

@section('js')
    <script src="{{ asset('js/dropzone.js') }}"></script>
    <script src="{{ asset('js/dropzone.min.js') }}"></script>


    <script>
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("tab");

            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            // Exit the function if any field in the current tab is invalid:
            if (n == 1 && !validateForm()) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form...
            if (currentTab >= x.length) {
                // ... the form gets submitted:
                document.getElementById("regForm").submit();
                return false;
            }
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function validateForm() {
            //   ev.paymentMethod.id+'_secret_'+clientSecret,


            // This function deals with validation of the form fields
            var x, y, z, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName(["input"]);
            z = x[currentTab].getElementsByTagName(["select"]);
            //    alert(z.length)
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].value == "") {
                    // add an "invalid" class to the field:
                    y[i].className += " invalid";
                    // and set the current valid status to false
                    valid = false;
                } else {
                    y[i].classList.remove("invalid");
                }
            }

            for (i = 0; i < z.length; i++) {
                if (!z[i].classList.contains('js-example-basic-multiple')) {
                    // If a field is empty...
                    if (z[i].value == "") {
                        // add an "invalid" class to the field:
                        z[i].className += " invalid";
                        // and set the current valid status to false
                        valid = false;
                    } else {
                        z[i].classList.remove("invalid");
                    }
                }

            }
            // If the valid status is true, mark the step as finished and valid:
            if (valid) {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid; // return the valid status
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }
    </script>



    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('slider/script.js')}}"></script>
    <script>




        $(document).ready(function () {

            $('.js-example-basic-multiple').select2();



            document.querySelector("#search_city")
                .nextSibling.querySelector(".select2-search__field")
                .addEventListener("keyup", function () {
                    var city = $(this).val();

                    $.ajax({
                        type: 'get',
                        url: '{{url("search/city")}}',
                        data: {'city': city},
                        async: false,

                        success: function (response) {
//$('#search_city').empty();

                            for (i = 1; i < response.data.length; i++) {
                                $('#search_city').append(`<option value="${response.data[i].key}" >${response.data[i].name} ${response.data[i].region} , ${response.data[i].country_name}</option>`);
                            }


                        }
                    });
                });


{{--            document.querySelector("#interest")--}}
{{--                .nextSibling.querySelector(".select2-search__field")--}}
{{--                .addEventListener("keyup", function () {--}}
{{--                    var interest = $(this).val();--}}

{{--                    $.ajax({--}}
{{--                        type: 'get',--}}
{{--                        url: '{{url("search/interest")}}',--}}
{{--                        data: {'interest': interest},--}}
{{--                        async: false,--}}

{{--                        success: function (response) {--}}
{{--//$('#search_city').empty();--}}

{{--                            for (i = 1; i < response.data.length; i++) {--}}
{{--                                $('#interest').append(`<option value="${response.data[i].id},${response.data[i].name}">${response.data[i].name}</option>`);--}}
{{--                            }--}}


{{--                        }--}}
{{--                    });--}}
{{--                });--}}

            $('#change_radius').change(function () {

                var val = $(this).val();
                $('#radius').empty().text(val + ' miles')
                // $(this).val(50);
            });
        });
    </script>
    <script>

        function uplaodImage() {

            //      jQuery.noConflict();
            $.ajax({
                type: 'get',
                url: "{{url('get/images')}}",

                success: function (response) {

                    $('.images-div').empty().append(response);

                }

            });


        }

    </script>

    <script>

        var headingNumber = 1;
        $(".heading-btn").click(() => {
            if (headingNumber < 5) {

                headingNumber++;
                $(".heading-feilds").append(
                    ` <input placeholder="Heading ${headingNumber}"  name="heading[]" class="mt-3">`
                );


            }
        });

        var textNumber = 1;
        $(".add-text").click(() => {
            if (textNumber < 5) {
                textNumber++;
                $(".text-feilds").append(
                    ` <input placeholder="Text ${textNumber}"  name="body[]" class="mt-3">`
                );

            }
        });

        var buttonNumber = 1;
        $(".add-button").click(() => {
            if (buttonNumber < 5) {
                buttonNumber++;
                $(".button-feilds").append(
                    `  <select name="btn[]" id="" class=" form-control  mb-2 mt-2">
                                    <option value="BOOK_TRAVEL" selected>BOOK TRAVEL</option>
                                    <option value="CONTACT_US">CONTACT US</option>
                                    <option value="DONATE">DONATE</option>
                                    <option value="DONATE_NOW">DONATE NOW</option>
                                    <option value="DOWNLOAD">DOWNLOAD</option>
                                    <option value="GET_DIRECTIONS">GET DIRECTIONS</option>
                                    <option value="GO_LIVE">GO LIVE</option>
                                    <option value="INTERESTED">INTERESTED</option>
                                    <option value="LEARN_MORE">LEARN MORE</option>
                                    <option value="LIKE_PAGE">LIKE PAGE</option>
                                    <option value="MESSAGE_PAGE">MESSAGE PAGE</option>
                                    <option value="SAVE">SAVE</option>
                                    <option value="SEND_TIP">SEND TIP</option>
                                    <option value="SHOP_NOW">SHOP NOW</option>
                                    <option value="SIGN_UP">SIGN UP</option>
                                    <option value="VIEW_INSTAGRAM_PROFILE">VIEW INSTAGRAM PROFILE</option>
                                    <option value="INSTAGRAM_MESSAGE">INSTAGRAM MESSAGE</option>
                                    <option value="LOYALTY_LEARN_MORE">LOYALTY LEARN MORE</option>
                                    <option value="PURCHASE_GIFT_CARDS">PURCHASE GIFT CARDS</option>
                                    <option value="PAY_TO_ACCESS">PAY TO ACCESS</option>
                                    <option value="SEE_MORE">SEE MORE</option>
                                    <option value="TRY_IN_CAMERA">TRY IN CAMERA</option>
                                    <option value="WHATSAPP_LINK">WHATSAPP LINK</option>
                                    <option value="GET_MOBILE_APP">GET MOBILE APP</option>
                                    <option value="INSTALL_MOBILE_APP">INSTALL MOBILE APP</option>
                                    <option value="USE_MOBILE_APP">USE_MOBILE APP</option>
                                    <option value="INSTALL_APP">INSTALL APP</option>
                                    <option value="USE_APP">USE APP</option>
                                    <option value="PLAY_GAME">PLAY GAME</option>
                                    <option value="WATCH_VIDEO">WATCH VIDEO</option>
                                    <option value="WATCH_MORE">WATCH MORE</option>
                                    <option value="OPEN_LINK">OPEN LINK</option>
                                    <option value="NO_BUTTON">NO BUTTON</option>
                                    <option value="LISTEN_MUSIC">LISTEN MUSIC</option>
                                    <option value="MOBILE_DOWNLOAD">MOBILE DOWNLOAD</option>
                                    <option value="GET_OFFER">GET OFFER</option>
                                    <option value="GET_OFFER_VIEW">GET OFFER VIEW</option>
                                    <option value="BUY_NOW">BUY NOW</option>
                                    <option value="BUY_TICKETS">BUY TICKETS</option>
                                    <option value="UPDATE_APP">UPDATE APP</option>
                                    <option value="BET_NOW">BET NOW</option>
                                    <option value="ADD_TO_CART">ADD TO CART</option>
                                    <option value="ORDER_NOW">ORDER NOW</option>
                                    <option value="SELL_NOW">SELL NOW</option>
                                    <option value="GET_SHOWTIMES">GET SHOWTIMES</option>
                                    <option value="LISTEN_NOW">LISTEN NOW</option>
                                    <option value="GET_EVENT_TICKETS">GET EVENT TICKETS</option>
                                    <option value="SEARCH_MORE">SEARCH MORE</option>
                                    <option value="PRE_REGISTER">PRE REGISTER</option>
                                    <option value="SWIPE_UP_PRODUCT">SWIPE UP PRODUCT</option>
                                    <option value="SWIPE_UP_SHOP">SWIPE UP SHOP</option>
                                    <option value="PLAY_GAME_ON_FACEBOOK">PLAY GAME ON FACEBOOK</option>
                                    <option value="CALL">CALL</option>
                                    <option value="MISSED_CALL">MISSED CALL</option>
                                    <option value="CALL_NOW">CALL NOW</option>
                                    <option value="APPLY_NOW">APPLY NOW</option>
                                    <option value="TRY_IT">TRY IT</option>

                                </select>


       Action button url:
 <input class="mt-2" placeholder="https://example.com" name="url[]">`
                );

            }
        })

        var imageNumber = 1;
        $(".image-btn").click(() => {
            //  jQuery.noConflict();
            $('#exampleModal').modal('show');
        })

        $(document).on('click', '.add_img', function () {

            var img = $(this).attr('img_name');
            var src = $(this).attr('src');
            if (imageNumber == 1) {

                $('.img1').attr('src', src);
            }
            if (imageNumber <= 5) {
                imageNumber++;
                $(".img-feilds").append(
                    `
   <div class="col-4">
   <input type="hidden" name="image[]" value="${img}">
<img src="${src}" class="w-100" alt="">
                                    </div>

     `
                );

                $('#exampleModal').modal('hide');


            }
        })


        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.img1')
                        .attr('src', e.target.result)

                };

                reader.readAsDataURL(input.files[0]);
            }

        }

        $('.perday_budget').keyup(function () {

            var budget = parseFloat($('.total_budget').val());
            var perday = parseFloat($('.perday_budget').val());

            $('.total_duration').val(parseInt(budget / perday));


        });

        $('.preview_1').change(function () {

            var btn = $('.text1_btn').val();
            btn = btn.replace('_', ' ');
            btn = btn.replace('_', ' ');
            var head = $('.heading1_btn').val();
            var body = $('.body1_btn').val();


            $('.action1_prev').empty().append(btn);
            $('.heading1_prev').empty().append(head);
            $('.body1_prev').empty().append(body);


        });
    </script>
@endsection


