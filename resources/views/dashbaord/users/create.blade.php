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
                    <h1 class="text-center">Create User</h1>
                    <form method="post" action="{{route('user.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group my-3">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" name="name" value="{{old('name')}}" class="form-control" id="" aria-describedby="emailHelp" placeholder="Enter name">
                            @if($errors->has('name'))

                                <span style="color: red">
                                        <strong>{{$errors->first('name')}}</strong>
                                    </span>
                            @endif

                        </div>

                        <div class="form-group my-3">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" value="{{old('email')}}" name="email" class="form-control" id="" aria-describedby="emailHelp" placeholder="Enter email">

                            @if($errors->has('email'))

                                <span style="color: red">
                                        <strong>{{$errors->first('email')}}</strong>
                                    </span>
                            @endif

                        </div>
                        <div class="form-group my-3">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" name="password" class="form-control" id="" placeholder="Password">
                            @if($errors->has('password'))

                                <span style="color: red">
                                        <strong>{{$errors->first('password')}}</strong>
                                    </span>
                            @endif

                        </div>




                        <div class="form-group mt-3 text-right">

                        <button type="submit" class="btn btn-primary">Submit</button>
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

