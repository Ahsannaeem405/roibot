<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <title>Roibod</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{url('/')}}">
                <img src="{{asset('images/logo.png')}}" width="100" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url('/')}}">Home </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/manage_view')}}">Manage Ads</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/create_add')}}">Create Ad</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/insight_view')}}">Insight</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/profile')}}">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/mediaGallery')}}">Media Gallery</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="#">Billing</a>
                    </li> --}}

                </ul>
                <form class="form-inline my-2 mr-lg-5 my-lg-0">
                    <div class="dropdown">
                        <!-- <button class="btn btn_profile" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> -->
                        <img src="{{asset('images/img_avatar.png')}}" class="rounded-circle" width="50" alt="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <!-- </button> -->
                        <div class="dropdown-menu text-center " aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Profile</a>
                            <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </div>
                </form>
            </div>
        </nav>
    </header>
    @yield('content')
     <!-- Footer -->
     <footer class="page-footer font-small special-color-dark pt-4">

        <!-- Footer Elements -->

        <!-- Footer Elements -->

        <!-- Copyright -->
        <div class="footer-copyright text-center py-2">© 2022 Copyright:
            <a href="#"> BrownTech.com</a>
        </div>
        <!-- Copyright -->

    </footer>
    <!-- Footer -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
