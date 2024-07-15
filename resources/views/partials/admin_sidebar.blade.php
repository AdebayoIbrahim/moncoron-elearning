<!-- <div class="d-flex flex-column flex-shrink-0 text-bg-purple my-sidebar">
    <a href="/admin/dashboard" class="p-4 d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <img src="../images/image-21@2x.png" class="bi pe-none me-2" width="42" height="42">
      <span class="fs-4 text-bold">MONCORAN</span>
    </a>
    <ul class="nav nav-pills flex-column mb-auto">
      <li>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} text-white">
        <i class="fa fa-dashboard mx-2"></i> Dashboard
        </a>
      </li>
      <li>
        <a href="{{ route('admin.students') }}" class="nav-link {{ request()->routeIs('admin.students') ? 'active' : '' }} text-white">
        <i class="fa fa-users mx-2"></i> Students
        </a>
      </li>
      <li>
        <a href="{{ route('admin.teachers') }}" class="nav-link text-white {{ request()->routeIs('admin.teacher') ? 'active' : '' }}">
        <i class="fa fa-users mx-2"></i> Teacher
        </a>
      </li>
      <li>
        <a href="{{ route('admin.lecturers') }}" class="nav-link text-white {{ request()->routeIs('admin.lecturer') ? 'active' : '' }}">
        <i class="fa fa-users mx-2"></i> Lecturer
        </a>
      </li>
      <li>
        <a href="{{ route('admin.courses') }}" class="nav-link {{ request()->routeIs('admin.courses') ? 'active' : '' }} text-white">
        <i class="fa fa-book mx-2"></i> Courses
        </a>
      </li>
      <li>
        <a href="" class="nav-link text-white">
        <i class="fa fa-book mx-2"></i> Dawah
        </a>
      </li>
      <li>
        <a href="" class="nav-link text-white">
        <i class="fa fa-users mx-2"></i> Community
        </a>
      </li>
      <li>
        <a href="" class="nav-link text-white">
        <i class="fa fa-graduation-cap mx-2"></i> MONCORAN
        </a>
      </li>
    </ul>
    <hr>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong>mdo</strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
        <li><a class="dropdown-item" href="#">New project...</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Sign out</a></li>
      </ul>
    </div>
  </div> -->




  <div class="sidebar col-md-3 col-lg-2 p-0 text-bg-purple">
      <div class="offcanvas-md offcanvas-end text-bg-purple" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title text-bold" id="sidebarMenuLabel"><img src="{{ asset('images/image-21@2x.png') }}" class="pe-none me-2" width="42" height="42"><span class="fs-4 text-bold">MONCORAN</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
          <ul class="nav nav-pills flex-column" style="height: 100vh;">
            <li class="nav-item">
              <a href="{{ route('admin.dashboard') }}" class="nav-link nav-links d-flex align-items-center gap-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} text-white">
                <i class="fa fa-dashboard mx-2"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.courses') }}" class="nav-link nav-links d-flex align-items-center gap-2 {{ (request()->routeIs('admin.courses') || request()->routeIs('admin.courseview')) ? 'active' : '' }} text-white">
                <i class="fa fa-book mx-2"></i> Courses
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.students') }}" class="nav-link nav-links d-flex align-items-center gap-2 {{ (request()->routeIs('admin.students') || request()->routeIs('admin.students.register')) ? 'active' : '' }} text-white">
                <i class="fa fa-users mx-2"></i> Students
              </a>
            </li>
            @if(session('current_course_id'))
            <li class="nav-item">
              <a href="{{ route('admin.courses.assessments', session('current_course_id')) }}" class="nav-link nav-links d-flex align-items-center gap-2 {{ request()->routeIs('admin.courses.assessments') ? 'active' : '' }} text-white">
                <i class="fa fa-file-text mx-2"></i> Assessments
              </a>
            </li>
            @endif
              <li class="nav-item">
              <a href="{{ route('admin.teachers') }}" class="nav-link nav-links d-flex align-items-center gap-2 {{ request()->routeIs('admin.teachers') ? 'active' : '' }} text-white">
                <i class="fa fa-book mx-2"></i> Teacher
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.lecturers') }}" class="nav-link nav-links d-flex align-items-center gap-2 {{ request()->routeIs('admin.lecturers') ? 'active' : '' }} text-white">
                <i class="fa fa-book mx-2"></i> Lecturer
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link nav-links d-flex align-items-center gap-2 text-white">
                <i class="fa fa-book mx-2"></i> Dawah
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link nav-links d-flex align-items-center gap-2 text-white">
                <i class="fa fa-users mx-2"></i> Community
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link nav-links d-flex align-items-center gap-2 text-white">
                <i class="fa fa-graduation-cap mx-2"></i> MONCORAN
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>