<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup | Moncoran</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/global.css') }}" rel="stylesheet">
  <link href="{{ asset('css/signup.css') }}" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link rel="icon" href="images/image-21@2x.png" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Baloo Paaji 2:wght@400;600&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" />
</head>

<body>
  <div class="signup row">
    <div class="vector-parent7 col-md-6"></div>
    <div class="col-md-6 email-input">
      <div class="full-name-password-container mt-2">
        <div class="age-input-wrapper">
          <div class="age-input">
            <div class="country-input">
              <a href="./"><img class="top-logo" width="50" loading="lazy" alt="" src="images/image-22@2x.png" /></a>
            </div>
            <div class="create-your-account-now-button-wrapper">
              <div class="create-your-account-now-button">
              @if (isset($error))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>{{ $error }}</strong>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
              @endif
                <h2 class="getting-started">Getting Started</h2>
                <div class="create-your-account-now-wrapper">
                  <div class="create-your-account">
                    Create your account now
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <form class="frame-parent51" action="{{ route('register') }}" method="post">
        @csrf
          <div class="form-group">
            <label class="label form-label">Email</label>
            <input required class="form-control myfg" placeholder="Enter Email" type="text" name="email"/>
          </div>
          <div class="form-group">
            <label class="label form-label">Full Name</label>
            <input required class="form-control myfg" placeholder="Enter full name" type="text" name="name"/>
          </div>
          <div class="month-dropdown">
            <div class="nb-name-inputted-container">
              <b class="nb">NB</b>
              <span class="span3"> </span>
              <span class="name-inputted-here">Name inputted here will appear same way on the
                certificate that will issued upon completion of a course
                by the student.</span>
            </div>
          </div>
          <div class="form-group">
            <label class="label form-label">Password</label>
            <input required class="form-control myfg" placeholder="Enter password" type="password" name="password"/>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <label class="label form-label">Age</label>
                <input required class="form-control myfgs" type="date" name="dob"/>
              </div>
              <div class="col-md-6">
                <label class="label form-label">Country</label>
                <select required class="form-control myfgs" name="country">
                  <option selected disabled value="">-- Select Country --</option>
                  <option>Nigeria</option>
                  <option>Ghana</option>
                  <option>Gambia</option>
                </select>
              </div>
            </div>
          </div>
          <div class="frame-parent55 mt-4">
            <button class="rectangle-parent31" id="groupButton1">
              <span class="signup1">Sign Up</span>
            </button>
            <div class="login-link mt-2">
              <div class="have-an-account-container" id="haveAnAccount">
                <span class="have-an-account">
                  <span class="have-an-account1">Have an account?</span>
                  <span class="span4"> </span>
                </span>
                <span class="login2">
                  <a class="login3" href="/login">Login</a>
                </span>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>

</html>