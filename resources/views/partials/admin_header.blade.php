<header class="navbar text-bg-purple sticky-top flex-md-nowrap p-0">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">
    <img src="{{ asset('images/image-21@2x.png') }}" class="pe-none me-2" width="42" height="42">  
    <span class="fs-4 text-bold">MONCORAN</span>
  </a>

  <!-- <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
    <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
  </form> -->

  <div class="flex-row dropdown mx-4">
    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="{{ asset('images/ellipse-62@2x.png') }}" alt="mdo" width="32" height="32" class="rounded-circle">
    </a>
    <ul class="dropdown-menu mxs text-small">
      <!-- <li><a class="dropdown-item" href="#">New project...</a></li>
      <li><a class="dropdown-item" href="#">Settings</a></li> -->
      <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fa fa-user"></i> Profile</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="/logout"><i class="fa fa-power-off"></i> Logout</a></li>
    </ul>
  </div>

  <!-- <ul class="navbar-nav flex-row">
    <li class="nav-item text-nowrap">
      <i class="fa fa-bell"></i>
    </li>
  </ul> -->

  <ul class="navbar-nav flex-row d-md-none">
    <!-- <li class="nav-item text-nowrap">
      <button class="nav-link px-3 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false" aria-label="Toggle search">
        <span class="fa fa-search"></span>
      </button>
    </li> -->
    <li class="nav-item text-nowrap">
      <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="fa fa-bars"></span>
      </button>
    </li>
  </ul>

  <!-- <div id="navbarSearch" class="navbar-search w-100 collapse">
    <input class="form-control w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
  </div> -->
</header>