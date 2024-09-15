  <div class="sidebar col-md-3 col-lg-2 p-0 text-bg-purple">
      <div class="offcanvas-md offcanvas-end text-bg-purple" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
          <div class="offcanvas-header">
              <h5 class="offcanvas-title text-bold" id="sidebarMenuLabel"><img src="{{ asset('images/image-21@2x.png') }}" class="pe-none me-2" width="42" height="42"><span class="fs-4 text-bold">MONCORAN</span></h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
              <ul class="nav nav-pills flex-column" style="height: 100vh;">
                  <li class="nav-item">
                      <a href="{{ route('student.dashboard') }}" class="nav-link nav-links d-flex align-items-center gap-2 {{ request()->routeIs('student.dashboard') ? 'active' : '' }} text-white">
                          <i class="fa fa-dashboard mx-2"></i> Learn
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="#" class="nav-link nav-links d-flex align-items-center gap-2 text-white">
                          <i class="fa fa-users mx-2"></i> Leaderboard
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('student.courses') }}" class="nav-link nav-links d-flex align-items-center gap-2 {{ (request()->routeIs('student.courses') || request()->routeIs('student.coursedesc')) ? 'active' : '' }} text-white">
                          <i class="fa fa-book mx-2"></i> Courses
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="" class="nav-link nav-links d-flex align-items-center gap-2 text-white">
                          <i class="fa fa-users mx-2"></i> Community
                      </a>
                  </li>
                  {{-- force_check_for_premium_courses --}}
                  @if(auth()->user()->user_type === 'premium')
                  <li class="nav-item">
                      <a href="{{ route('student.courses') }}" class="nav-link nav-links d-flex align-items-center gap-2 {{ (request()->routeIs('student.courses') || request()->routeIs('student.courses.register')) ? 'active' : '' }} text-white">
                          <i class="fas fa-crown mx-2"></i>
                          Special Courses
                      </a>
                  </li>
                  @endif
                  <li class="nav-item">
                      <a href="" class="nav-link nav-links d-flex align-items-center gap-2 text-white">
                          <i class="fa fa-graduation-cap mx-2"></i> MONCORAN
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="" class="nav-link nav-links d-flex align-items-center gap-2 text-white">
                          <i class="fa fa-book mx-2"></i> Dawah
                      </a>
                  </li>
              </ul>
          </div>
      </div>
  </div>
