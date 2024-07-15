<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Moncoran</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/global.css') }}" rel="stylesheet">
  <link href="{{ asset('css/login.css') }}" rel="stylesheet">
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
    <div class="col-md-6 vector-parent1">
    </div>
    <div class="col-md-6 login-inner">
      <div class="frame-parent25">
        <div class="frame-parent26 mt-2">
        <a href="./"><img width="50" loading="lazy" alt="" src="images/image-22@2x.png" /></a>
        </div>
        <div class="frame-wrapper23">
          <div class="frame-parent27">
            <div class="log-in-to-your-account-parent">
              <h2 class="log-in-to">Log in to your Account</h2>
              <div class="welcome-back-select-to-login-wrapper">
                <div class="welcome-back-select">
                  Welcome back, select to login
                </div>
              </div>
            </div>
            @if (session('alert'))
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <strong>{{ session('alert') }}</strong>
              </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="frame-wrapper24">
              <div class="frame-parent28">
                <button class="rectangle-parent14">
                  <div class="google-1-wrapper">
                    <img class="google-1-icon" alt="" src="images/google-1@2x.png" />
                  </div>
                  <div class="google2">Google</div>
                </button>
                <button class="rectangle-parent15">
                  <div class="vector-frame">
                    <img class="vector-icon3" alt="" src="images/vector1.svg" />
                  </div>
                  <div class="facebook2">Facebook</div>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="frame-wrapper25">
          <div class="frame-parent29">
            <div class="frame-parent30">
              <div class="line-wrapper4">
                <div class="frame-child35"></div>
              </div>
              <div class="or-continue-with">or continue with Email</div>
              <div class="line-wrapper4">
                <div class="frame-child35"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="frame-wrapper26">
          <form class="frame-form" action="{{ route('login') }}" method="post">
          @csrf
                <div class="form-group">
                  <label class="label form-label">Email</label>
                  <input required class="form-control myfg" name="email" placeholder="Enter Email" type="email" />
                </div>
                <div class="form-group mb-4">
                  <label class="label form-label">Password</label>
                  <input required class="form-control myfg" name="password" placeholder="Enter password" type="password" />
                </div>
                <div class="frame-parent34">
                  <div class="vector-parent2">
                    <input class="rectangle-input" type="checkbox" id="mycheck"/>
                    <div class="remember-me-wrapper">
                      <label for="mycheck" class="remember-me">Remember Me</label>
                    </div>
                  </div>
                  <div class="forgot-password-wrapper">
                      <a class="forgot-password" id="forgotPasswordText" href="/forgot">forgot password?</a>
                  </div>
                </div>
                <div class="welcomeback-inner">
                <button type="submit" class="mb-2 mt-4 rectangle-parent18" id="groupButton2">
                  <div class="login1">Login</div>
                </button>
              </div>
              <div class="dont-have-an-container" id="dontHaveAn">
                <span class="dont-have-an-account">
                  <span class="dont-have-an">Don’t have an account?</span>
                  <span class="span2"> </span>
                </span>
                <span class="create-an-account">
                  <a class="create-an-account1" href="/signup">Create an account</a>
                </span>
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