<header class="navbar text-bg-purple sticky-top flex-md-nowrap p-0">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">
        <img src="{{ asset('images/image-21@2x.png') }}" class="pe-none me-2" width="42" height="42">
        <span class="fs-4 text-bold">MONCORAN</span>
    </a>

    <!-- <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
    <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
  </form> -->


    {{-- notifications --}}
    <div class="dropdown" style="margin-left: auto; margin-top: 0.5rem; cursor: pointer;">
        <div class="position-relative" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-bell" style="color: #fff; font-size: 1.67rem;"></i>
            @if(count(auth()->user()->notifications) != 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{count(auth()->user()->notifications)}}
                <span class="visually-hidden">unread messages</span>
            </span>
            @endif
        </div>
        {{-- Dropdown menu --}}
        <ul class="dropdown-menu dropdown-menu-end mt-2 parent_container" aria-labelledby="dropdownMenuButton" style="width: 500px;padding-inline:1rem;border-color:transparent;overflow-x:hidden;border-radius: 0.8rem">
            {{-- <heade-clear-btn></heade-clear-btn> --}}
            <div style="display: flex;justify-content: space-between; align-items: center; padding-block: .5rem">
                <h4>Notifications</h4>
                <button type="button" class="btn btn-info btn-sm">Clear</button>
            </div>
            @if(count(auth()->user()->notifications) != 0)
            @foreach(auth()->user()->notifications as $notification)
            <div class="notification">
                {{-- sender-avater start--}}
                <div class="avatar_sender_container">
                    @if($notification->data['sender_name'] == 'admin')
                    <img src={{asset('images/ellipse-62@2x.png')}} alt="sender_avatar" width="40" height="40">
                    @else
                    {{-- show a default-avatar --}}
                    @endif
                </div>
                {{-- sender-avatar-ends-here --}}
                {{-- flex-text-messgae-contents --}}
                <div class="notification_content">
                    {{-- sender-name --}}
                    @if($notification->data['sender_name'] == 'admin')
                    <p><b>Moncoran</b> just sent you a new notification!</p>
                    @else
                    <p>{{$notification->data['sender_name'].'sent a notification' }}</p>
                    @endif
                    {{-- text -- default-there-shouldbe a text--}}
                    <p class="not_text">{{$notification->data['message']}}</p>
                    {{-- audio?ifpresent --}}
                    {{-- attached-link --}}
                    {!! isset($notification->data['attached_link']) ?
                    '<p class="not_text"><a href="'.$notification->data['attached_link'].'">Explore the Course</a></p>'
                    : null !!}
                </div>

                {{-- is-read-check --}}

                @if($notification->read_at === null)
                <span class="mt-2" style="width: 8px; height : 6px; border-radius: 50%; background: #236ad6;"></span>
                @endif
            </div>
            @endforeach
            @else
            <div class="notification">No Current Notifications</div>
            @endif
            <div class="bottom_nav_notifications">
                <div class="line_full"></div>
                <div class="mt-1">
                    <button type="button" class="btn btn-sm btn-transparent" style="color: blue" onclick="handlemarkRead()">
                        <span><i class="fa-solid fa-check-double"></i></span>
                        <span style="margin-left: .6rem;font-size:1rem">Mark all as Read</span>
                    </button>
                </div>
            </div>
        </ul>
    </div>
    <div class=" flex-row dropdown mx-4">
        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('images/ellipse-62@2x.png') }}" alt="mdo" width="32" height="32" class="rounded-circle">
        </a>
        <ul class="dropdown-menu mxs text-small">
            <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fa fa-user"></i> Profile</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
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
<script>
    function handlemarkRead(arg) {
        // console.log(arg)
    }

</script>
