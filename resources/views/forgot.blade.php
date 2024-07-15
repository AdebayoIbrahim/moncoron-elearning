<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot | Moncoran</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/global.css') }}" rel="stylesheet">
  <link href="{{ asset('css/forgot.css') }}" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link rel="icon" href="images/image-21@2x.png" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Baloo Paaji 2:wght@400;600&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" />
</head>

<body>
  <div class="row login">
    <div class="col-md-6 vector-parent11">
    </div>
    <div class="col-md-6 login-inner">
      <div class="frame-parent25">
        <div class="frame-parent26 mt-2">
        <a href="./"><img width="50" loading="lazy" alt="" src="images/image-22@2x.png" /></a>
        </div>
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="headings">
            <p class="fheading text-bold">Forgot password</p>
            <span class="sheading" id="">Please enter your Email address.<br /> We'll send instructions to help reset your <br />password.</span>
        </div>
        <div class="frame-wrapper26">
          <form class="frame-form" action="{{ route('forgot') }}" method="post">
          @csrf
                <div class="form-group">
                  <label class="label form-label">Email</label>
                  <input required class="form-control myfg" name="email" placeholder="Enter Email" type="email" />
                </div>
                <div class="welcomeback-inner">
                    <button type="submit" class="mb-2 mt-4 rectangle-parent18" id="groupButton2">
                    <div class="login1">Continue</div>
                    </button>
                </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>

</html>