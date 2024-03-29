@extends('layout.mainlayout')
<link rel="stylesheet" href="{{asset('css/profile.css')}}">
@section('content')
    <!-- Profile Section -->
    <section class="profile py-5 section">
        <div class="container my-3">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="py-3 profilePic">
                        <img src="{{asset('images/profile/'.$user->profile.'')}}" id="my_image"
                             class="img-fluid profile-img rounded-circle" alt="Profile Picture">
                    </div>

                    <div class="badge-button">
                        <a class="" href="#"><i class="fa fa-plus-circle faa-icon" id="pofile_btn"
                                                aria-hidden="true"></i></a>
                    </div>

                </div>

                <div class="col-12 col-lg-9 col-md-9 profile-border bg-light text-darkBlue">
                    <div class="row py-5 px-3">
                        <div class="col-12 col-md-6 col-lg-6">
                            <form class="social-media-Link p-4 bg-white" method="post"
                                  action="{{url('profile/update')}}" enctype="multipart/form-data">
                                @csrf
                                <input class="d-none" onchange="readURL(this);" type="file" name="profile" id="profile">
                                <div class="form-group">
                                    <label for="clientName"><b> Name:</b></label>
                                    <input type="text" required class="form-control form-control-sm" name="name"
                                           value="{{$user->name}}" id="clientName">
                                </div>
                                <div class="form-group">
                                    <label for="email"><b>Email:</b></label>
                                    <input type="email" required class="form-control form-control-sm"
                                           value="{{$user->email}}" readonly id="email">
                                </div>

                                {{--                                <div class="form-group">--}}
                                {{--                                    <label for="password"><b>Old Password:</b></label>--}}
                                {{--                                    <input type="password" class="form-control form-control-sm" name="old_password"--}}
                                {{--                                           id="old_password">--}}


                                {{--                                </div>--}}

                                {{--                                <div class="form-group">--}}
                                {{--                                    <label for="password"><b>New Password:</b></label>--}}
                                {{--                                    <input type="password" name="password" class="form-control form-control-sm"--}}
                                {{--                                           id="password">--}}
                                {{--                                    @if($errors->has('password'))--}}

                                {{--                                        <span style="color: red">--}}
                                {{--                                        <strong>{{$errors->first('password')}}</strong>--}}
                                {{--                                    </span>--}}
                                {{--                                    @endif--}}
                                {{--                                </div>--}}
                                <div class="text-center">
                                    <button class="btn px-4 btn-update" type="submit">Update</button>
                                </div>
                            </form>
                        </div>


                        <div class="modal fade" id="facebook" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog " role="document">
                                <form action="{{url('update/fb')}}" method="post">
                                    @csrf



                                    <div class="modal-content">
                                        <div class="modal-header ">
                                            <h5 class="modal-title d-flex" id="exampleModalLabel">
                                                <span>Connect with facebook</span>
                                                <div class="d-flex justify-content-center" style="background-color: #dce7ea;border-radius: 50%;width: 30px;height: 30px">
                                                    <a href="{{url('connect-with-facebook')}}"  target="_blank"> <i class="fa fa-info mt-1" style=""></i> </a>
                                                </div>

                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12 d-none">
                                                    <lable>App ID</lable>
                                                    <input type="text" name="fb_client" value="{{$user->fb_client}}"
                                                           class="form-control">
                                                </div>
                                                <div class="col-lg-12 d-none">
                                                    <lable>Secret ID</lable>
                                                    <input type="text" name="fb_secret" value="{{$user->fb_secret}}"
                                                           class="form-control">
                                                </div>
                                                <div class="col-lg-12">
                                                    <lable>Page ID</lable>
                                                    <input type="text" required class="form-control" value="{{$user->fb_page}}" name="fb_page">
                                                    {{--                                                    <select name="fb_page" class="form-control" id="">--}}
                                                    {{--                                                        <option selected  value="">select page</option>--}}
                                                    {{--                                                        @foreach($pages as $page)--}}

                                                    {{--                                                            <option  {{$user->fb_page==$page->id ? 'selected' : null}} value="{{$page->id}}">{{$page->name}}</option>--}}
                                                    {{--                                                        @endforeach--}}
                                                    {{--                                                    </select>--}}


                                                </div>
                                                <div class="col-lg-12">
                                                    <lable>Account ID</lable>

                                                    <input type="text" required class="form-control" value="{{$user->fb_account}}" name="fb_account">
                                                    {{--                                                    <select name="fb_account" class="form-control" id="">--}}
                                                    {{--                                                        <option selected  value="">select Account</option>--}}
                                                    {{--                                                        @foreach($accounts as $account)--}}

                                                    {{--                                                            <option  {{$user->fb_account==$account->account_id ? 'selected' : null}} value="{{$account->account_id}}">{{$account->account_id}}</option>--}}
                                                    {{--                                                        @endforeach--}}
                                                    {{--                                                    </select>--}}


                                                </div>
                                                <div class="col-lg-12 ">
                                                    <lable>Access TOKEN</lable>
                                                    <input type="text" readonly  id="fb_token" name="fb_token" value="{{$user->fb_token}}"
                                                           class="form-control">
                                                </div>
                                                <div class="col-lg-12 text-center my-3">
                                                    <fb:login-button
                                                        scope="public_profile,email,ads_management,ads_read,pages_show_list"
                                                        onlogin="checkLoginState();">
                                                    </fb:login-button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" id="fb_submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>




                                </form>

                            </div>
                        </div>

                        <div class="modal fade" id="google" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog " role="document">
                                <form action="{{url('update/google')}}" method="post">
                                    @csrf


                                    <div class="modal-content">
                                        <div class="modal-header ">
                                            <h5 class="modal-title d-flex" id="exampleModalLabel">
                                                <span>Connect with google</span>
                                                <div class="d-flex justify-content-center" style="background-color: #dce7ea;border-radius: 50%;width: 30px;height: 30px">
                                                    <a href="{{url('connect-with-google')}}"  target="_blank"> <i class="fa fa-info mt-1" style=""></i> </a>
                                                </div>

                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12 d-none">
                                                    <lable>Client ID</lable>
                                                    <input type="text"  name="gg_client" value="{{$user->gg_client}}"
                                                           class="form-control">
                                                </div>
                                                <div class="col-lg-12 d-none">
                                                    <lable>Client secret</lable>
                                                    <input type="text" name="gg_secret" value="{{$user->gg_secret}}"
                                                           class="form-control">
                                                </div>
                                                <div class="col-lg-12 d-none">
                                                    <lable>Developer Token</lable>
                                                    <input type="text" name="gg_dev" value="{{$user->gg_dev}}"
                                                           class="form-control">
                                                </div>
                                                <div class="col-lg-12 d-none">
                                                    <lable>Manager Account ID</lable>
                                                    <input type="text" name="gg_manager" value="{{$user->gg_manager}}"
                                                           class="form-control">
                                                </div>

                                                <div class="col-lg-12">
                                                    <lable>Customer Account ID</lable>
                                                    <input type="text" name="gg_customer" value="{{$user->gg_customer}}"
                                                           required    class="form-control">
                                                </div>

                                                <div class="col-lg-12 d-none">
                                                    <lable>Access Token</lable>
                                                    <input type="text" name="gg_access" value="{{$user->gg_access}}"
                                                           class="form-control">
                                                </div>

                                                <div class="col-lg-12 d-none">
                                                    <lable>Refresh Token</lable>
                                                    <input type="text" name="gg_refresh" value="{{$user->gg_refresh}}"
                                                           class="form-control">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>


                                </form>

                            </div>
                        </div>



                        <div
                            class="col-12 col-md-6 col-lg-6 mt-5 mt-md-2 social-media-link d-flex justify-content-center">
                            <div class="">
                                <div class="form-group social-media-Link px-4 py-3 bg-white">
                                    <label for="linkGoogle"><b>Link Ads Account:</b></label><br>


                                    <button class="btn btn-primary w-100 mt-2" data-toggle="modal"
                                            data-target="#facebook"><i class="fab fa-facebook-f"></i> Link with Facebook
                                    </button>
                                    <br>

                                    <button class="btn btn-danger w-100 mt-2" data-toggle="modal"
                                            data-target="#google"><i class="fab fa-google-plus-g"></i> Connect
                                        with Google
                                    </button>

                                    {{-- <div class="form-group social-media-Link px-4 py-3 bg-white">
                                        <label for="linkFacebook"><b>Link Facebook Ads Account:</b></label>
                                        <input type="email" class="form-control form-control-sm my-2" id="linkFacebook">
                                        <div class="text-right pt-2">
                                            <button class="btn px-3 btn-update">Save</button>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>


    <!-- Profile Section -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId : '{{$admin->facebook_app}}',
                cookie : true,
                xfbml : true,
                version : 'v14.0'
            });

            FB.AppEvents.logPageView();

        };
        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        function checkLoginState() {
            FB.getLoginStatus(function(response) {
                var token=response.authResponse.accessToken;
                $('#fb_token').val(token);
                $('#fb_submit').click();

            });
        }
    </script>
    <script>
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#my_image')
                        .attr('src', e.target.result);
                };


                reader.readAsDataURL(input.files[0]);
            }
        }


        $("#pofile_btn").click(function () {

            $('#profile').click();


        });
    </script>

@endsection
