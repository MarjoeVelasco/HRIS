@include('users.userslayouts.library')

<!DOCTYPE html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
 
        <title>TALMS</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                /* background-color: #fff; */
                color: #636b6f;
                font-weight: 200;
                height: 100vh;
                margin: 0;
                padding:0; 
                overflow-x:hidden;
               
            }

            

            body { overflow-x: hidden; width: 100vw;}

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 5em;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 20px;
            }

            #background
            {
                background-image: url("/images/background/opening.png");
                background-size:cover;
                background-repeat:no-repeat;
                background-attachment: fixed;
                background-position: center;
            }

            #header_img
            {
                position: absolute;
                left: 0px;
                top: 20px;
            }

            #icon_header
            {
                max-width: 95%;
  height: auto;
            }

            .features_icon
            {
                height:150px;
            }

            .feature_icon_container
            {
                border-radius:10px;
                background:white;
                margin:20px;
                padding:10px;
                border:solid 1px #ffbf82;
            }
            .home_btn
            {
                padding-left:50px !important;
                padding-right:50px !important;
            }

           

            @media only screen and (max-width: 1180px) {
                .features_icon
                    {
                    height:110px;
                    }
                }


           

            @media only screen and (max-width: 980px) {
                .title {
                font-size: 8vw;
                    }
                    .features_icon
                    {
                    height:90px;
                    }
                }

                @media only screen and (max-width: 880px) {
              
                    .features_icon
                    {
                    height:140px;
                    
                    }
                    .title {
                        margin-top:200px;
                    }
                }

                @media only screen and (max-width: 865px) {
              
                    .title {
                        margin-top:200px;
                    }
                }

                @media only screen and (max-width: 767px) {
              
                    .title {
                        margin-top:200px;
                    }
                    .features_icon
                    {
                    height:80px;
                    }

                    .feature_icon_container
                    {
                    width:40%;
                    margin-left:auto;
                    margin-right:auto;
                    }
          }
            
        </style>
    </head>
    <body>
        <div class="container_fluid" id="background">
            <div class="flex-center position-ref full-height">
                <div id="header_img">
                <img src="/images/icons/banner_logo.png" id="icon_header">   
                </div>
                
                <!--Title with button -->
                <div class="row">
                    
                    <div class="col">
                    <br><br><br><br><br><br><br><br><br><br><br>
                        <div class="title m-b-md center-block text-center">
                        Timekeeping, Accomplishments, and Leave Management System
                        </div>
                    </div>

                <!--New line -->
                <div class="w-100"></div>
                <!--New line -->

                    <div class="col flex-center">
                        @if (Route::has('login'))
                        
                        @auth
                            <a href="{{ url('/home') }}"><button class="btn btn-primary btn-lg home_btn" id="btn_home">HOME</button></a>
                        @else
                        
                            <a href="{{ route('login') }}"><button class="btn btn-primary btn-lg home_btn" id="btn_login">LOGIN</button></a>

                        @if (Route::has('register'))
                                <a href="{{ route('register') }}"><button class="btn btn-secondary btn-lg home_btn" id="btn_login" class="button">REGISTER</button></a>
                        @endif
                        @endauth
                    
                        @endif
                    </div>
                
                <!--New line -->
                <div class="w-100"></div>
                <!--New line -->
                <br><br><br>

                
                <div class="row" id="background" style="margin:auto;">
                    <!--Official Website Icon start-->
                    <div class="col-md feature_icon_container">
                        <div class="text-center">
                            <a href="https://ils.dole.gov.ph" target="_blank">
                            <div class="col">
                                <img class="features_icon" src="/images/icons/website.png">
                            </div>

                            <div class="col">
                                <label>Official Website</label>
                            </div>
                            </a>
                        </div>
                    </div>

                    <!--Official Website Icon start-->


                    <!--Webmail Icon start-->
                    <div class="col-md feature_icon_container">
                        <div class="text-center">
                            <a href="https://outlook.office.com/mail/" target="_blank">
                            <div class="col">
                                <img class="features_icon" src="/images/icons/webmail.png">
                            </div>

                            <div class="col">
                                <label>Webmail</label>
                            </div>
                            </a>
                        </div>
                    </div>
                    <!--Webmail Icon end-->

                    <!--Cloud Storage Icon start-->
                    <div class="col-md feature_icon_container">
                        <div class="text-center">
                            <a href="https://ilstest06-my.sharepoint.com/" target="_blank">
                            <div class="col">
                                <img class="features_icon" src="/images/icons/cloud_storage.png">
                            </div>

                            <div class="col">
                                <label>Cloud Storage</label>
                            </div>
                            </a>
                        </div>
                    </div>
                    <!--Cloud Storage Icon end-->

                    <!--IT Helpdesk Icon start-->
                    <div class="col-md feature_icon_container">
                        <div class="text-center">
                            <a href="https://nas.ils.dole.gov.ph/osticket/upload/" target="_blank">
                                <div class="col">
                                    <img class="features_icon" src="/images/icons/helpdesk.png">
                                </div>
                    
                                <div class="col">
                                    <label>IT Helpdesk</label>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!--IT Helpdesk Icon end-->

                    <!--Health Declaration   -->
                    <div class="col-md feature_icon_container">
                        <div class="text-center">
                            <a href="https://forms.office.com/Pages/ResponsePage.aspx?id=guZUuEHcEUWp_nB9SS795qHwVtOnCWhHgXk0RBUn9P9UQUo0TUlMSjlCRERSTFRERFpESjQyNUFaUi4u" target="_blank">
                                <div class="col">
                                    <img class="features_icon" src="/images/icons/health_declaration.png">
                                </div>

                                <div class="col">
                                <label>Health Declaration</label>
                                </div>
                    </a>

                    </div>

                    </div>
                  <!--Health Declaration end-->
                    </div>





                </div>
            </div>  
            
        <br><br><br>
        <br>
        </div>

 <!--Underconstruction   
<footer class="breadcrumb">
	<p style="width: 100%;text-align: center;">Institute for Labor Studies || Advocacy and Publications Division - Information Technology Unit &copy; {{ now()->year }}</p>
</footer>
 -->     
    </body>
</html>
