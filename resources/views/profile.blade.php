@extends('layout.mainlayout')
<link rel="stylesheet" href="{{asset('css/profile.css')}}">
@section('content')
    <!-- Profile Section -->
    <section class="profile py-5 section">
        <div class="container my-3">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="py-3 profilePic">
                        <img src="{{asset('images/profile/'.$user->profile.'')}}" id="my_image" class="img-fluid profile-img rounded-circle" alt="Profile Picture">
                    </div>

                    <div class="badge-button">
                        <a class="" href="#"><i class="fa fa-plus-circle faa-icon" id="pofile_btn" aria-hidden="true"></i></a>
                    </div>

                </div>

                <div class="col-12 col-lg-9 col-md-9 profile-border bg-light text-darkBlue">
                    <div class="row py-5 px-3">
                        <div class="col-12 col-md-6 col-lg-6">
                            <form class="social-media-Link p-4 bg-white" method="post" action="{{url('profile/update')}}" enctype="multipart/form-data">
                                @csrf
                                <input class="d-none" onchange="readURL(this);" type="file" name="profile" id="profile">
                                <div class="form-group">
                                    <label for="clientName"><b> Name:</b></label>
                                    <input type="text" required class="form-control form-control-sm" name="name" value="{{$user->name}}" id="clientName">
                                </div>
                                <div class="form-group">
                                    <label for="email"><b>Email:</b></label>
                                    <input type="email"  required class="form-control form-control-sm" value="{{$user->email}}" readonly id="email">
                                </div>

                                <div class="form-group">
                                    <label for="password"><b>Old Password:</b></label>
                                    <input type="password" class="form-control form-control-sm" name="old_password" id="old_password">


                                </div>

                                <div class="form-group">
                                    <label for="password"><b>New Password:</b></label>
                                    <input type="password" name="password" class="form-control form-control-sm" id="password">
                                    @if($errors->has('password'))

                                        <span style="color: red">
                                        <strong>{{$errors->first('password')}}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <button class="btn px-4 btn-update" type="submit">Update</button>
                                </div>
                            </form>
                        </div>


                        <div class="modal fade" id="facebook" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog " role="document">
                                <form action="{{url('update/fb')}}" method="post">
                                    @csrf


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Connect with facebook</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <lable>CLIENT ID</lable>
                                                    <input type="text" name="fb_client" value="{{$user->fb_client}}" required class="form-control">
                                                </div>
                                                <div class="col-lg-12">
                                                    <lable>SECRET ID</lable>
                                                    <input type="text" name="fb_secret" value="{{$user->fb_secret}}" required class="form-control">
                                                </div>
                                                <div class="col-lg-12">
                                                    <lable>PAGE ID</lable>
                                                    <input type="number" name="fb_page" value="{{$user->fb_page}}" required class="form-control">
                                                </div>
                                                <div class="col-lg-12">
                                                    <lable>ACCOUNT ID</lable>
                                                    <input type="number" name="fb_account" value="{{$user->fb_account}}" required class="form-control">
                                                </div>
                                                <div class="col-lg-12">
                                                    <lable>ACCESS TOKEN</lable>
                                                    <input type="text" name="fb_token" value="{{$user->fb_token}}" required class="form-control">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6 mt-5 mt-md-2 social-media-link d-flex justify-content-center">
                            <div class="">
                                <div class="form-group social-media-Link px-4 py-3 bg-white">
                                    <label for="linkGoogle"><b>Link Ads Account:</b></label><br>


                                        <button class="btn btn-primary w-100 mt-2" data-toggle="modal" data-target="#facebook"><i class="fab fa-facebook-f"></i> Link with Facebook</button><br>

                                        <button class="btn btn-danger w-100 mt-2"><i class="fab fa-google-plus-g"></i> Link with Google </button>

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
    function readURL(input) {


        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#my_image')
                    .attr('src', e.target.result);
            };



            reader.readAsDataURL(input.files[0]);
        }
    }


    $("#pofile_btn").click(function() {

        $('#profile').click();


    });
</script>

  @endsection
