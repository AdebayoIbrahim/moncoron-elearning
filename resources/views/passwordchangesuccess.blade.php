<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password | Moncoran</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/global.css') }}" rel="stylesheet">
  <link href="{{ asset('css/verify.css') }}" rel="stylesheet">
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
        <div class="headings" style="text-align: center !important;">
            <img src="images/group.svg" /><br /><br />
            <p class="fheading text-bold">Password Changed <br /><br /><br />Successfully</p>
            <span class="sheading" id="">Your password has been changed successfully</span>
        </div>
        <div class="frame-wrapper26">
          <form class="frame-form" action="" method="post" id="myform">
          @csrf
                <div class="welcomeback-inner">
                    <a href="/login" type="submit" class="no-decor mb-2 mt-4 rectangle-parent18" id="groupButton2">
                        <div class="login1">Continue</div>
                    </a>
                </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script>
    $(document).ready(function(){
        $(document).on('input', '#password', function(){
            pass = $('#password').val();
            conpass = $('#conpassword').val();
            if(conpass != ''){
                if(pass != conpass){                        
                    $('#vpass').removeClass('hidden');
                    $('#vpass').html('<b>New Password and Confirm New Password does not match</b>');
                }
                else{
                    $('#vpass').addClass('hidden');   
                }
            }
            else{
                $('#vpass').addClass('hidden');   
            }
        });

        $(document).on('input', '#conpassword', function(){
            pass = $('#password').val();
            conpass = $('#conpassword').val();
            if(pass != conpass){
                $('#vpass').removeClass('hidden');
                $('#vpass').html('<b>New Password and Confirm New Password does not match</b>');
            }
            else{
                $('#vpass').addClass('hidden');   
            }
        });

    });
  </script>
</body>

</html>