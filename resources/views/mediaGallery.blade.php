@extends('layout.mainlayout')
@section('content')
    <!-- Media Gallery Section -->

    <section class="mediaGallery">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="d-flex justify-content-around">
                        <div class="d-flex">
                            <h2>Media Gallery</h2>
                            <button class="btn btn-primary ml-2">Add New</button>
                        </div>

                        <div class="pt-2">
                            <button class="btn btn-primary">Select</button>
                            <button class="btn btn-primary">Delete</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <div class="row">
                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>

                <div class="col-12 my-3">
                    <div class="row">
                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>

                        <div class="col-3">
                            <img src="./images/img_avatar.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- Media Gallery Section -->


   @endsection
