@extends('dashbaord.layout.main')

@section('content')

    <style>
        .tableParent {
            border-radius: 20px;
        }

        .pagination {
            list-style: none;
            display: flex;

        }

        .font-20 {
            font-size: 20px;
            cursor: pointer;
        }

        .pagination li {
            padding: 5px;
        }

        #table-data_wrapper {
            overflow: auto;
        }


        #table-data_previous, #table-data_next {
            background-color: #20b5cc;

            padding: 5px;
            margin: 5px;
            border-radius: 5px;
        }



    </style>

    <main class="my-5">


        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-xs-12 tableParent bg-white p-5">
                    <table id="table-data" class="table table-bordered table-hover table-responsive">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>

                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>

                                <td>

                                    <div>
                                        <a href="#" url="{{url("admin/user/delete/$user->id")}}" class="delete"> <i
                                                class="fa fa-trash text-danger p-2 font-20"></i></a>
                                                <a href="{{url("admin/user/edit/$user->id")}}" class="edit">  <i class="fa fa-edit text-primary p-2 font-20"></i></a>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </main>

@endsection

@section('js')
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.11.4/datatables.min.js"></script>


    <script>
        $('#table-data').DataTable();
        $('#table-data').css('display','table') ;

    </script>

    <script>
        $(document).ready(function () {
            $('.delete').click(function () {

                var url = $(this).attr('url');

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this record!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        location.href=url;
                    } else {

                    }
                });
            });
        });
    </script>
@endsection

