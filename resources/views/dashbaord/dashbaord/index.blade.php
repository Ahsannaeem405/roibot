@extends('dashbaord.layout.main')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

            <div class="row">


                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mt-0">
                                                <h5 class="card-title">Total Users</h5>
                                            </div>
                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle" data-feather="dollar-sign"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h1 class="mt-1 mb-3">{{$user}}</h1>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>





        </div>
    </main>
@endsection

@section('js')
    <script src="{{asset('assets/js/app.js')}}"></script>

@endsection
