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

    .select2-container {
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

    .card:hover {
        transform: none !important;
    }

    .btn {
        box-shadow: none !important;
    }

    .slider-box {
        width: 100%;
        margin: 25px auto
    }


    .slider {
        margin: 25px 0
    }

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
                    <button type="button" class="btn btn-secondary" onClick=""
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
                    <form id="regForm" method="post" action="{{'/post/add/google'}}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="advert_type" class="text" value="{{$advert}}">

                        <!-- One "tab" for each step in the form: -->

                        <div class="tab mt-4">
                            <h3 class="text-center step-heading mx-auto">Step: 2</h3>


                            <p class="mt-3 w-100">
                                Geo Location Country:
                                <select id="countryChnage" class="form-control  mt-2 js-example-basic-multiple w-100"
                                        name="countries[]"
                                        multiple="multiple">
                                    @foreach($country as $con)
                                        <option value="{{$con->name}}">{{$con->name}}</option>
                                    @endforeach
                                </select>
                            </p>

                            <p class="mt-3 w-100">
                                Geo Location City:
                                <select id="search_city" class="form-control mt-2 js-example-basic-multiple city"
                                        name="city[]" multiple="multiple" >


                                </select>
                            </p>

                            <p class="mt-3 w-100">
                                Add Keywords (Max 80 characters and 10 words):
                                <textarea name="keywords" class="form-control" id="" cols="2" rows="3"></textarea>
                            </p>

                            {{--                            <p class="mt-3">--}}
                            {{--                                Radius <span id="radius">10 miles</span>:--}}
                            {{--                                <input type="range" id="change_radius" name="radius" value="10">--}}
                            {{--                            </p>--}}

                            <p class="my-3">

                            <div class="slider-box">
                                <label style="border: none" for="priceRange">Audience:</label>
                                <select class="form-control mt-2" name="age">

                                    <option selected value="AGE_RANGE_18_24">18 to 24</option>
                                    <option value="AGE_RANGE_25_34">25 to 34</option>
                                    <option value="AGE_RANGE_35_44">35 to 44</option>
                                    <option value="AGE_RANGE_45_54">45 to 54</option>
                                    <option value="AGE_RANGE_55_64"> 55 to 64</option>
                                    <option value="AGE_RANGE_65_UP">65 UP</option>
                                </select>
                            </div>


                            <select class="form-control mt-2" name="gender">

                                <option selected value="MALE">Male</option>
                                <option value="FEMALE">Female</option>
                                <option value="UNDETERMINED">Undetermined gender</option>
                            </select>
                            </p>


                        </div>
                        <div class="tab">
                            <h3 class="text-center step-heading mx-auto">Step: 1</h3>


                            <p class="my-3">
                                Campaign Title
                                <input type="text" class="my-2" name="title" placeholder="Campaign Title">
                            </p>

                            <p class="my-3">
                                Select advertising Channel Type:


                                <select name="chanel" id="chanel" class="form-control mt-2">

                                    <option selected value="SEARCH">SEARCH</option>
                                    <option value="DISPLAY">DISPLAY</option>

                                </select>

                            </p>


                            <p class="my-3">
                                Daily budget:

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


                        <div class="tab">
                            <h3 class="text-center step-heading mx-auto">Step: 3</h3>


                            <p class="my-3 ">
                            <div class="all_preview SEARCH_preview">


                            <div class="d-flex justify-content-between align-items-center my-2">
                                Add Heading
                                <button class="heading-btn" type="button">+</button>
                            </div>
                            <div class="heading-feilds row">

                                <div class="col-lg-6">
                                    <input placeholder="Heading 1" class="preview_1 heading1_btn"
                                           value="This is your heading part 1" name="heading[]">
                                </div>
                                <div class="col-lg-6">
                                    <input placeholder="Heading 1" class="preview_1 heading2_btn"
                                           value="This is your heading part 2" name="heading2[]">
                                </div>
                            </div>
                            </div>
                            </p>

                            <p class="my-3 ">
                            <div class="all_preview SEARCH_preview">


                            <div class="d-flex justify-content-between align-items-center my-2">
                                Add Body Text
                                <button class="add-text" type="button">+</button>
                            </div>
                            <div class="text-feilds">
                                <input placeholder="Text 1" value="This is your body" name="body[]"
                                       class="preview_1 body1_btn">
                            </div>
                            </div>
                            </p>


                            <p class="my-3">
                            <div class="">


                                <div class="d-flex justify-content-between align-items-center ">
                                    Action button url:
                                    <button class="add-button" value="Action" type="button">+</button>
                                </div>
                                <div class="button-feilds">

                                    {{--                                <input placeholder="Button 1" value="Action" class="preview_1 text1_btn mb-1"--}}
                                    {{--                                       name="btn[]">--}}

                                    <input class="my-2 preview_1 url1_btn" placeholder="https://example.com"
                                           value="https://example.com" name="url[]">

                                </div>
                            </div>
                            </p>

                            <p class="my-3">
                            <div class="all_preview DISPLAY_preview" style="display: none">

                                Image Dimension
                                <select name="dimentions" id="">
                                    <option selected value="300 x 100">300 x 100</option>
                                    <option value="750 x 300">750 x 300</option>
                                    <option value="750 x 200">750 x 200</option>
                                    <option value="750 x 100">750 x 100</option>
                                    <option value="950 x 900">950 x 900</option>
                                    <option value="88 x 31">88 x 31</option>
                                    <option value="220 x 90">220 x 90</option>
                                    <option value="980 x 90">980 x 90</option>
                                    <option value="240 x 133">240 x 133</option>
                                    <option value="970 x 66">970 x 66</option>
                                    <option value="600 x 314">600 x 314</option>
                                    <option value="728 x 90">728 x 90</option>
                                    <option value="160 x 600">160 x 600</option>
                                    <option value="736 x 414">736 x 414</option>
                                    <option value="970 x 250">970 x 250</option>
                                </select>


                                <div class="d-flex justify-content-between align-items-center my-2">
                                    Add Image
                                    <button class="image-btn" type="button">+</button>
                                </div>

                                <div class="img-feilds row">


                                </div>
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
                    <div class="preview1 all_preview SEARCH_preview">

                        <a class="a_card">

                            <div class="box-shadow p-0 overflow-hidden p-3">


                                <div class="position-relative">
                                    <span class="ml-2 ">Ad . <span class="divurl_1"> www.example.com/ </span></span>


                                    <h5 class=" heading_fb text-white heading1_prev ml-2 mt-1"
                                        style="color: blue!important;">This is your heading part 1 | This is your
                                        heading part 2</h5>

                                </div>
                                <div class="ml-2 d-flex justify-content-between">


                                    <p class="body1_prev">This is your body</p>

                                </div>

                            </div>
                        </a>


                    </div>
                    <div class="preview1 all_preview DISPLAY_preview" style="display: none">

                        <a class="a_card">

                            <div class="box-shadow p-0 overflow-hidden p-3">


                                <div class="position-relative">
                                    <img src="{{asset('images/adsdata.jpg')}}" class="img-fluid w-100 img1" alt="">

                                </div>


                            </div>
                        </a>


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
            $('.js-example-basic-tag').select2({
                tags:true,
                maximumSelectionLength:10
            });


            $(document.body).on("change", "#countryChnage", function () {
                var city = $(this).val();


                $.ajax({
                    type: 'get',
                    url: '{{url("search/city/google")}}',
                    data: {'city': city},
                    async: false,

                    success: function (response) {
                        console.log(response[0].geoTargetConstant);

                        for (i = 1; i < response.length; i++) {
                            $('#search_city').append(`<option value="${response[i].geoTargetConstant.resourceName}">${response[i].geoTargetConstant.name}</option>`);
                        }


                    }
                });
            });


            $('#change_radius').change(function () {

                var val = $(this).val();
                $('#radius').empty().text(val + ' miles')
                // $(this).val(50);
            });


            $('#chanel').change(function () {
                var chanel = $(this).val();
                $('.all_preview').hide();
                $('.' + chanel + '_preview').show();

            });
        });
    </script>
    <script>

        function uplaodImage() {

            // jQuery.noConflict();
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
                    ` <div class="col-lg-6 mt-2">
                                    <input placeholder="Heading 1" class=" "
                                           value="This is your heading part 1" name="heading[]">
                                </div>
                                <div class="col-lg-6 mt-2">
                                <input placeholder="Heading 1" class=" "
                                       value="This is your heading part 2" name="heading2[]">
                                </div>`
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
                    ` Action button url:
 <input class="my-2" placeholder="https://example.com"  name="url[]">`
                );

            }
        })

        var imageNumber = 1;
        $(".image-btn").click(() => {
            //   jQuery.noConflict();
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


            var head = $('.heading1_btn').val();
            var head2 = $('.heading2_btn').val();
            var body = $('.body1_btn').val();
            var url = $('.url1_btn').val();


            $('.heading1_prev').empty().append(head + ' | ' + head2);
            $('.body1_prev').empty().append(body);
            $('.divurl_1').empty().append(url);


        });
    </script>
@endsection


