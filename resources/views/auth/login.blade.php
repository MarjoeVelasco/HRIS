<!-- fontawesome icon -->
<link rel="stylesheet" href="{{ asset('fonts/fontawesome/css/fontawesome-all.min.css') }}">
<!-- animation css -->
<link rel="stylesheet" href="{{ asset('plugins/animation/css/animate.min.css') }}">
<!-- vendor css -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<title>TALMS | Login</title>

 <div class="auth-wrapper" style="background-color: #e2e2e2;
    background-image: linear-gradient(to right, #974478, #007cc2);">
        <div class="auth-content">

        <p class="text-center font-weight-bold text-white h3" style="opacity:0.7;">TALMS</p>
        <p></p>
            
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                    <img src="{{url('/images/icons/icon_header.png')}}" alt="ILS logo header"/>
                    </div>
                    <h3 class="text-secondary"><b>Login</b></h3>
                   
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                    <div class="input-group mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"  placeholder="Email">

                        @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                    </div>
                    <div class="input-group mb-4">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="password">

                         @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                    </div>


                    
            
                    <button class="btn btn-primary shadow-2 mb-4"><i class="feather icon-unlock"></i> Login</button>
                    <input type="hidden" name="recaptcha" id="recaptcha">
                    <!--
                    
                    @if (Route::has('password.request'))
                    <p class="mb-2 text-muted">Forgot password? <a href="{{ route('password.request') }}">Reset</a></p>
                                @endif
                    -->

                    <!-- <p class="mb-0 text-muted">Don’t have an account? <a href="register">Signup</a></p> -->
                    <p><a style="color:#c93a93;" href="{{ route('forget.password.get') }}">Forgot Your Password?</a></p>
                    
                </form>
                </div>
            </div>
            
            @if(session('error'))
            <br>
            <div class="card mb-4">
                <div class="card-body text-center">
                  
                   <span style="color:red;"><b> {{session('error')}}</b></span>
                   

                </div>
            </div>
            @endif

           

         

            





        </div>
    </div>

    

<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
<script>
         grecaptcha.ready(function() {
             grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'login'}).then(function(token) {
                if (token) {
                  document.getElementById('recaptcha').value = token;
                }
             });
         });
</script>