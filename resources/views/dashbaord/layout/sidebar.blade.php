<nav id="sidebar" class="sidebar js-sidebar">


    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand text-center" href="{{url('/admin/index')}}" >

            <img src="{{asset('images/logo.png')}}" width="100" alt="">
        </a>

        <ul class="sidebar-nav">


            <li class="sidebar-item active">
                <a class="sidebar-link" href="{{url('admin/index')}}">
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>


            <li class="sidebar-item ">
                <a class="sidebar-link" href="{{url('admin/users')}}">
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">USERS</span>
                </a>
            </li>
            <li class="sidebar-item ">
                <a class="sidebar-link" href="{{url('admin/create/user')}}">
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">CREATE USER</span>
                </a>
            </li>







        </ul>


    </div>


</nav>
