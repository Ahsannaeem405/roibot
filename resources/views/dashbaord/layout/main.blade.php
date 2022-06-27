<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{{auth()->user()->name}}</title>
    <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">

    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.4/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
<style>
    .text-right{
        text-align: right;
    }

    a{

        text-decoration: none !important;
    }
    .dropbtn {
        background-color: #73e077;
        border-radius: 20px;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-toggle:after {
padding: 0!important;
    }
    .avatar{
        height: 40px !important;
    }

    .dropdown-content {
        padding: 5px;
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        height: 200px;
        overflow: auto;
        margin-left: -30px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 10px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #f1f1f1}

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        /*background-color: #3e8e41;*/
    }
</style>

</head>


<body>
<div class="wrapper">
    @include('dashbaord.partials.component2')

    @include('dashbaord.layout.sidebar')

    <div class="main">
        @include('dashbaord.layout.navbar')

        @yield('content')

        @include('dashbaord.layout.footer')


    </div>
</div>

@yield('js')

</body>

</html>
