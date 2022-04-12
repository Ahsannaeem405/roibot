@extends('layout.mainlayout')
<style>
    * {
        box-sizing: border-box;
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
    .preview1{
        margin: 60px auto;
        border-radius: 15px;

        border: 1px solid #000;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }
    .preview{
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
    input.invalid {
        background-color: #ffdddd;
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
    <section>
        <div class="container-fluid">


            <div class="mt-5">
                <h1>Create Your Add:</h1>
            </div>
            <div class="row">
                <div class="col-md-8 col-12 mt-2">
                    <form id="regForm">

                        <!-- One "tab" for each step in the form: -->
                        <div class="tab mt-4">
                            <h3 class="text-center step-heading mx-auto">Step: 1</h3>
                            <p>
                                Select Advertisement Goals:
                                <select class="form-control mt-2">
                                    <option selected>Choose Goal ...</option>
                                    <option>Views</option>
                                    <option>Clicks</option>
                                    <option>Traffic</option>
                                    <option>Orders</option>
                                </select>
                            </p>
                            {{-- <p class="mt-3">
                                Select Advertisement:
                                <select class="form-control mt-2">
                                    <option selected>Choose Goal ...</option>
                                    @if (isset($advert))
                                        <option value="1" @if ($advert == 1) selected @endif>Facebook
                                        </option>
                                        <option value="2" @if ($advert == 2) selected @endif>Google</option>
                                    @else
                                        <option value="1">Facebook</option>
                                        <option value="2">Google</option>
                                    @endif



                                </select>
                            </p> --}}
                        </div>
                        <div class="tab">
                            <h3 class="text-center step-heading mx-auto">Step: 2</h3>

                            <p class="my-3">
                                Add URL:
                                <input placeholder="Site url . . ." oninput="this.className = ''" name="email">
                            </p>
                            <p class="my-3">
                                Audience:
                                <select class="form-control mt-2">
                                    <option selected>Age Limit</option>
                                    <option>10 to 18 </option>
                                    <option>18 to 25</option>
                                    <option>25 to 50</option>
                                    <option>50+</option>
                                </select>
                                <select class="form-control mt-2">
                                    <option selected>Gender</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Both</option>
                                </select>
                            </p>

                            <p class="my-3">
                                Budget:
                                <input placeholder="Total Budget" oninput="this.className = ''" name="Total_budget"
                                    type="number" class=" mt-2">
                                <input placeholder="Per Day" oninput="this.className = ''" name="perDay_budget"
                                    class="mt-2" type="number">

                            </p>
                            <p class="my-3">
                                Duration:
                                <input placeholder="Add durations(days)" oninput="this.className = ''" name="" type="number"
                                    class=" mt-3">
                            </p>
                        </div>

                        <div class="tab">
                            <h3 class="text-center step-heading mx-auto">Step: 3</h3>

                            <p class="my-3">
                            <div class="d-flex justify-content-between align-items-center my-2">
                                Add Heading <button class="heading-btn" type="button">+</button>
                            </div>
                            <div class="heading-feilds">
                                <input placeholder="Heading 1" oninput="this.className = ''" name="dd">
                            </div>
                            </p>

                            <p class="my-3">
                            <div class="d-flex justify-content-between align-items-center my-2">
                                Add Body Text <button class="add-text" type="button">+</button>
                            </div>
                            <div class="text-feilds">
                                <input placeholder="Text 1" oninput="this.className = ''" name="dd">
                            </div>
                            </p>

                            <p class="my-3">
                            <div class="d-flex justify-content-between align-items-center my-2">
                                Add Image
                            </div>
                            <div class="img-feilds">

                                <div class="row">
                                    <div class="col-3">
                                        <div class="Addver-img">
                                            <img src="{{ asset('images/Default_Image.png') }}" alt=""
                                                class="img-fluid default-img">
                                            <!-- <input type="image" hidden class="input-img"> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </p>
                        </div>
                        <div class="tab">
                            <h3 class="text-center step-heading mx-auto">Step: 4</h3>
                        </div>
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
                                    <button class="btn btn-secondary learn">Learn More</button>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="box-shadow p-0 overflow-hidden">



                            <div class="position-relative">

                                    <img src="{{asset('images/ads.jpg')}}" class="img-fluid" alt="">

                                    <h4 class="position-absolute heading_fb">Helloo</h4>

                            </div>
                            <div class="p-3 d-flex justify-content-between ">


                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                            <a href="#" class="my-auto"><i class="fas fa-angle-right font_icon "></i></a>
                        </div>

                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    </section>
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
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].value == "") {
                    // add an "invalid" class to the field:
                    y[i].className += " invalid";
                    // and set the current valid status to false
                    valid = false;
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
        var headingNumber = 1;
        $(".heading-btn").click(() => {
            if (headingNumber < 5) {
                headingNumber++;
                $(".heading-feilds").append(
                    ` <input placeholder="Heading ${headingNumber}" oninput="this.className = ''" name="dd" class="mt-3">`
                    );


            }
        });

        var textNumber = 1;
        $(".add-text").click(() => {
            if (textNumber < 5) {
                textNumber++;
                $(".text-feilds").append(
                    ` <input placeholder="Text ${textNumber}" oninput="this.className = ''" name="dd" class="mt-3">`
                    );

            }
        })

        $(".default-img").click(() => {
            $(".input-img").click();
        })
    </script>
@endsection
