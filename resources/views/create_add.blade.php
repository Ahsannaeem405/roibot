@extends('layout.mainlayout')
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
                        <div class="tab mt-4">
                            <h3 class="text-center step-heading mx-auto">Step: 1</h3>
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

                        </div>
                        <div class="tab">
                            <h3 class="text-center step-heading mx-auto">Step: 2</h3>




                            <p class="my-3">
                                Campaign Title
                                <input type="text" name="title" placeholder="Campaign Title" >
                            </p>


                            <p class="my-3">
                                Audience:
                                <select class="form-control mt-2" name="age">
                                    <option selected value="">Age Limit</option>
                                    <option value="10 to 18">10 to 18</option>
                                    <option value="18 to 25">18 to 25</option>
                                    <option value="25 to 50">25 to 50</option>
                                    <option value="51 to 200">50+</option>
                                </select>
                                <select class="form-control mt-2" name="gender">
                                    <option selected value="">Gender</option>
                                    <option value="1">Male</option>
                                    <option value="0">Female</option>
                                    <option>Both</option>
                                </select>
                            </p>

                            <p class="my-3">
                                Budget:

                                <input placeholder="Per Day" name="perday_budget"
                                       class="mt-2 perday_budget" type="number">

                            </p>

                        </div>

                        <div class="tab">
                            <h3 class="text-center step-heading mx-auto">Step: 3</h3>

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
                                Action button
                                <button class="add-button" value="Action" type="button">+</button>
                            </div>
                            <div class="button-feilds">

                                <input placeholder="Button 1" value="Action" class="preview_1 text1_btn mb-1" name="btn[]">

                                <input placeholder="URL 1" name="url[]">

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
                                            <img src="{{asset('images/adsdata.jpg')}}" class="img-fluid img1" alt="">

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

                                        <img src="{{asset('images/adsdata.jpg')}}" class="img-fluid img1" alt="">

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
            // This function deals with validation of the form fields
            var x, y,z, i, valid = true;
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
                }
                else {
                    y[i].classList.remove("invalid");
                }
            }

            for (i = 0; i < z.length; i++) {

                // If a field is empty...
                if (z[i].value == "") {
                    // add an "invalid" class to the field:
                    z[i].className += " invalid";
                    // and set the current valid status to false
                    valid = false;
                }
                else {
                    z[i].classList.remove("invalid");
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

        function uplaodImage() {


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
        })   ;

        var buttonNumber = 1;
        $(".add-button").click(() => {
            if (buttonNumber < 5) {
                buttonNumber++;
                $(".button-feilds").append(
                    ` <input placeholder="button ${buttonNumber}"  name="btn[]" class="mt-3 mb-2">



 <input placeholder="URL ${buttonNumber}" name="url[]">`
                );

            }
        })

        var imageNumber = 1;
        $(".image-btn").click(() => {
            $('#exampleModal').modal('show');
        })

        $(document).on('click','.add_img',function () {

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

        $('.preview_1').keyup(function () {

            var btn = $('.text1_btn').val();
            var head = $('.heading1_btn').val();
            var body = $('.body1_btn').val();


            $('.action1_prev').empty().append(btn);
            $('.heading1_prev').empty().append(head);
            $('.body1_prev').empty().append(body);


        });
    </script>
@endsection

