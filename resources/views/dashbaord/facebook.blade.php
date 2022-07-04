@extends('dashbaord.layout.main')

@section('content')
    <style>
        .form-style{
            border-radius: 20px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }
        .dropify-wrapper {
            height: 100px;
        }

        .file-icon p {
            font-size: 20px;
        }
    </style>


    <main class="my-5">


        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-lg-6 m-auto bg-white p-5 form-style" >
                    <h1 class="text-center">Update Facebook</h1>
                    <form method="post" action="{{route('facebook.update')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group my-3">
                            <label for="exampleInputEmail1">APP ID</label>
                            <input type="text" value="{{$rec->facebook_app}}" name="app" class="form-control" id="" aria-describedby="emailHelp" >

                        </div>

                        <div class="form-group my-3">
                            <label for="exampleInputEmail1">SECRET ID</label>
                            <input type="text" value="{{$rec->facebook_secret}}" name="secret" class="form-control" id="" aria-describedby="emailHelp">

                        </div>


                        <div class="form-group my-3">
                            <label for="exampleInputEmail1">ACCESS TOKEN</label>
                            <input type="text" value="{{$rec->facebook_token}}" name="token" class="form-control" id="" aria-describedby="emailHelp" >

                        </div>

                        <div class="form-group my-3 text-center">

                            <button class="btn btn-primary">update</button>
                        </div>

                    </form>

                </div>


            </div>
        </div>

    </main>

@endsection

@section('js')
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

    <script>
        $('.dropify').dropify();
    </script>

@endsection

