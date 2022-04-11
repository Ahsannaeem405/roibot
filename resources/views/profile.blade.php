@extends('layout.mainlayout')
<link rel="stylesheet" href="{{asset('css/profile.css')}}">
@section('content')
    <!-- Profile Section -->
    <section class="profile py-5">
        <div class="container my-3">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="py-3 profilePic">
                        <img src="images/img_avatar.png" class="img-fluid profile-img rounded-circle" alt="Profile Picture">
                    </div>
                    <div class="badge-button">
                        <a class="" href="#"><i class="fa fa-plus-circle faa-icon" aria-hidden="true"></i></a>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-update mb-3 mb-lg-0 mb-md-0">Update</button>
                    </div>
                </div>

                <div class="col-12 col-lg-9 col-md-9 profile-border bg-light text-darkBlue">
                    <div class="row py-5 px-3">
                        <div class="col-12 col-md-6 col-lg-6">
                            <form class="social-media-Link p-4 bg-white">
                                <div class="form-group">
                                    <label for="clientName"><b>Client Name:</b></label>
                                    <input type="text" class="form-control form-control-sm" id="clientName">
                                </div>
                                <div class="form-group">
                                    <label for="email"><b>Email:</b></label>
                                    <input type="email" class="form-control form-control-sm" id="email">
                                </div>
                                <div class="form-group">
                                    <label for="userName"><b>User Name:</b></label>
                                    <input type="text" class="form-control form-control-sm" id="userName">
                                </div>
                                <div class="form-group">
                                    <label for="password"><b>Password:</b></label>
                                    <input type="password" class="form-control form-control-sm" id="password">
                                </div>
                                <div class="text-center">
                                    <button class="btn px-4 btn-update">Update</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 mt-5 mt-md-2 social-media-link d-flex justify-content-center">
                            <div class="">
                                <div class="form-group social-media-Link px-4 py-3 bg-white">
                                    <label for="linkGoogle"><b>Link Ads Account:</b></label><br>


                                        <button class="btn btn-primary w-100 mt-2"><i class="fab fa-facebook-f"></i> Link with Facebook</button><br>

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


  @endsection
