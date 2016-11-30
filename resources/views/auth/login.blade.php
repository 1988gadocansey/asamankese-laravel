<!--<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Takoradi Polytechnic">
    <meta name="author" content="Takoradi Polytechnic">
    <title>SRMS | Welcome</title>
    <meta name="msapplication-TileColor" content="#9f00a7">
    
   Favicons
  <link rel="icon" href="assets/favicon.png" sizes="32x32">
   Favicons
  <link rel="apple-touch-icon-precomposed" href="assets/favicon.png">
   For iPhone 
  <meta name="msapplication-TileColor" content="#00bcd4">
  <meta name="msapplication-TileImage" content="assets/favicon.png">
   For Windows Phone 
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Ubuntu:300,400,600,700&amp;lang=en" />

   CORE CSS
   <style>
    
html{
    font-family: 'Ubuntu', sans-serif;
    font-size: 112.5%;
    
}   
    
.gad{
    font-family: "Ubuntu", "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 112.5%;
}
    
    
    
    
      .prefix ~ input, .input-field .prefix ~ textarea {
          margin-left: 3rem;
          width: 92%;
          width: calc(100% - 3rem);
          padding-left: 6px;
      }
.input-field .prefix ~ input, .input-field .prefix ~ textarea {
    margin-left: 3rem;
    width: 92%;
    width: calc(100% - 3rem);
    padding-left: 6px;
}

  </style>

  <link href='{!! url( "public/public/logins/css/materialize.css")  !!}' type="text/css" rel="stylesheet" media="screen,projection">
  <link href='{!! url( "public/public/logins/css/style.css")  !!}' type="text/css" rel="stylesheet" media="screen,projection">
     Custome CSS    
    <link href="public/public/logins/css/custom-style.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="public/public/logins/css/page-center.css" type="text/css" rel="stylesheet" media="screen,projection">

   INCLUDED PLUGIN CSS ON THIS PAGE 
  <link href="public/public/logins/css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="public/public/logins/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
  </head>

<body class="gad" style="">
   Start Page Loading 
  <div class="row">
          <div class="input-field col s12 center">
              <img src="public/public/logins/images/tpconnect_logo.png" alt="" class="" style="width:190px;height:auto">
              <p class=" " ><h5>TPConnect - One account for all apps </h5></p>
          </div>
        </div>
  <div id="loader-wrapper">
      <div id="loader"></div>        
      <div class="loader-section section-left"></div>
      <div class="loader-section section-right"></div>
  </div>
   End Page Loading 
  <div>
    if there are login errors, show them here 
		 @if (count($errors) > 0)

                <div class="uk-form-row">
                    <div class="alert alert-danger" style="background-color: red;color: white">
                       
                          <ul>
                            @foreach ($errors->all() as $error)
                              <li> {!!  $error  !!} </li>
                            @endforeach
                      </ul>
                </div>
              </div>
            @endif
  </div>
  
  <div id="login-page" class="row">
       
    <div class="col s12 z-depth-1 card-panel">
        <form class="login-form"  method="POST"  action="{{ url('login') }}">
             {!! csrf_field() !!}
             <div>&nbsp;</div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person-outline prefix"></i>
            <input id="username" type="email" required="" name="email">
            <label for="username" class="center-align">Username</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password" type="password" required="" name="password">
            <label for="password">Password</label>
          </div>
        </div>
        <div class="row">          
          <div class="input-field col s12 m12 l12  login-text">
              <input type="checkbox" id="remember-me" />
              <label for="remember-me">Remember me</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
              
                       <button type="submit" class="btn waves-effect waves-light col s12">Login</button> 
                           
          </div>
        </div>
        <div class="row">
            <center><small style="font-size: 11px">&copy <?php //echo  date('Y'); ?> | Tpconnect Team</small></center>         
        </div>

      </form>
    </div>
  </div>



   ================================================
    Scripts
    ================================================ 

   jQuery Library 
  <script type="text/javascript" src="public/public/logins/js/jquery-1.11.2.min.js"></script>
  materialize js
  <script type="text/javascript" src="public/public/logins/js/materialize.js"></script>
  prism
  <script type="text/javascript" src="public/public/logins/js/prism.js"></script>
  scrollbar
  <script type="text/javascript" src="public/public/logins/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

  plugins.js - Some Specific JS codes for Plugin Settings
  <script type="text/javascript" src="public/public/logins/js/plugins.js"></script>

</body>
</html>
        -->
        
        <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Takoradi Polytechnic">
    <meta name="author" content="Takoradi Polytechnic">
    <title>SRMS | Asamankese SHS</title>
    <meta name="msapplication-TileColor" content="#9f00a7">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Favicons-->
  <link rel="icon" href="assets/favicon.png" sizes="32x32">
  <!-- Favicons-->
  <link rel="apple-touch-icon-precomposed" href="assets/favicon.png">
  <!-- For iPhone -->
  <meta name="msapplication-TileColor" content="#00bcd4">
  <meta name="msapplication-TileImage" content="assets/favicon.png">
  <!-- For Windows Phone -->

 <style>
    
html{
    
    
    
}
body{
    
    
    
}
</style>
  <!-- CORE CSS-->
  
  <link href='{!! url( "public/logins/css/materialize.css")  !!}' type="text/css" rel="stylesheet" media="screen,projection">
  <link href='{!! url( "public/logins/css/style.css")  !!}' type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
    <link href="public/logins/css/custom-style.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="public/logins/css/page-center.css" type="text/css" rel="stylesheet" media="screen,projection">

  <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
  <link href="public/logins/css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="public/logins/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
  
</head>

<body class="blue-grey">
  <!-- Start Page Loading -->
  <div id="loader-wrapper">
      <div id="loader"></div>        
      <div class="loader-section section-left"></div>
      <div class="loader-section section-right"></div>
  </div>
  <!-- End Page Loading -->
  <div>
   <!-- if there are login errors, show them here -->
		 @if (count($errors) > 0)

                <div class="uk-form-row">
                    <div class="alert alert-danger" style="background-color: red;color: white">
                       
                          <ul>
                            @foreach ($errors->all() as $error)
                              <li> {!!  $error  !!} </li>
                            @endforeach
                      </ul>
                </div>
              </div>
            @endif
  </div>
  <div class="row">
          <div class="input-field col s12 center">
              <img src="public/logins/images/logo.png" alt="" class="" style="width:121px;height:auto">
            <p class="center login-form-text" style='color:white'>Students Records Management System</p>
          </div>
        </div>
  <div id="login-page" class="row">
    <div class="col s12 z-depth-4 card-panel">
        <form class="login-form"  method="POST"  action="{{ url('login') }}">
             {!! csrf_field() !!}
             <div>&nbsp;</div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person-outline prefix"></i>
            <input id="username" type="text" required=""    name="fund">
            <label for="username" class="center-align">Staff ID</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password" type="password" required="" name="password">
            <label for="password">Password</label>
          </div>
        </div>
        <div class="row">          
          <div class="input-field col s12 m12 l12  login-text">
              <input type="checkbox" id="remember-me" />
              <label for="remember-me">Remember me</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
              <table>
                  <tr>
                      <Td><button type="submit" class="btn waves-effect waves-light col s12">Login</button></Td>
                      <td><a href='{{url("password/reset")}}'/>Forgot password??</a></td>
             
                  </tr>
              </table>
          </div>
        </div>
        <div class="row">
            <center><small style="font-size: 11px">&copy <?php echo  date('Y'); ?> | gadeksystems</small></center>         
        </div>

      </form>
    </div>
  </div>



  <!-- ================================================
    Scripts
    ================================================ -->

  <!-- jQuery Library -->
  <script type="text/javascript" src="public/logins/js/jquery-1.11.2.min.js"></script>
  <!--materialize js-->
  <script type="text/javascript" src="public/logins/js/materialize.js"></script>
  <!--prism-->
  <script type="text/javascript" src="public/logins/js/prism.js"></script>
  <!--scrollbar-->
  <script type="text/javascript" src="public/logins/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

  <!--plugins.js - Some Specific JS codes for Plugin Settings-->
  <script type="text/javascript" src="public/logins/js/plugins.js"></script>

</body>
</html>
        