<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Css link -->
    <link rel="stylesheet" href="{{asset('css/login2.css')}}">
    <!-- Bootstrap link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <section class="login-page">
        <div class="container">
            <div class="row d-flex justify-content-center pt-5">
                <div class="col-12 col-xl-4 col-lg-6 col-md-8">
                    <div class="login-details">
                        <div class="logo mx-auto pt-4">
                            <img src="{{asset('images/logo.png')}}" alt="Logo" class="img-fluid">
                        </div>
                        <div class="login-form pt-4">
                            <form>
                                <h4 class="">Login to Your Account</h4>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Username" required="required">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Password" required="required">
                                </div>
                                <div class="form-group clearfix">
                                    <label class="checkbox-inline"><input type="checkbox"><span class="pl-2">Remember
                                            me</span></label>
                                    <a href="#" class="forgot-link">Forgot Password?</a>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <a class="btn px-5 py-2" href="{{asset('/')}}">Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>






    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
