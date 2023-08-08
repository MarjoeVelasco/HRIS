<!-- fontawesome icon -->
<link rel="stylesheet" href="{{ asset('fonts/fontawesome/css/fontawesome-all.min.css') }}">
<!-- animation css -->
<link rel="stylesheet" href="{{ asset('plugins/animation/css/animate.min.css') }}">
<!-- vendor css -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<title>TALMS | Register</title>

<div class="auth-wrapper"  style="background-color: #e2e2e2;
    background-image: linear-gradient(to right, #974478, #007cc2);">
    <div class="auth-content col-md-6">
        
        <div class="card" >
            <div class="card-body text-center">
                <div class="mb-4">
                <img src="{{url('/images/icons/icon_header.png')}}" alt="ILS logo header"/>
                </div>
               <h3 class="text-secondary"><b>Create Account</b></h3>
               
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                
                {{ csrf_field() }}
                   
                   
                    <div class="row">
                    <div class="form-group col-md-12">
                    <label>Profile Picture</label>
                      <div class="input-group">
                            <input id="image" type="file" class="form-control" name="image" required>
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>



                    
                    </div>
                    </div>


                   

                    <div class="row">
                    <div class="input-group mb-3 col-md-6">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Display Name">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>
                    

                    
                    <div class="input-group mb-3 col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>
                    </div>




                    <div class="row">
                    <div class="input-group mb-5 col-md-6">
                            
                         <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>
                    

                    
                    <div class="input-group mb-5 col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">   
                    </div>
                    </div>








                    <div class="input-group mb-3">
                         <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" required autocomplete="lastname" placeholder="Lastname">
                                @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>

                    <div class="input-group mb-3">
                         <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" required autocomplete="firstname" placeholder="Firstname">
                                @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>

                    <div class="input-group mb-3">
                         <input id="middlename" type="text" class="form-control @error('middlename') is-invalid @enderror" name="middlename" autocomplete="middlename" placeholder="Middlename">
                                @error('middlename')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>

                    <div class="input-group mb-5">
                         <input id="extname" type="text" class="form-control @error('extname') is-invalid @enderror" name="extname" autocomplete="extname" placeholder="Extension Name ( Jr, Sr, I, II, III )">
                                @error('extname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>


                    
                   

                    <div class="row">
                    <div class="input-group mb-3 col-md-3">
                    <input id="employee_number" type="text" class="form-control @error('employee_number') is-invalid @enderror" name="employee_number" required autocomplete="employee_number" placeholder="Employee Number">
                                @error('employee_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>
                    

                    
                    <div class="input-group mb-3 col-md-9">
                    <input id="position" type="text" class="form-control @error('position') is-invalid @enderror" name="position" required autocomplete="position" placeholder="Position">
                                @error('position')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>
                    </div>

                    <div class="row">
                    <div class="input-group mb-3 col-md-6">
                    <input id="item_number" type="text" class="form-control @error('item_number') is-invalid @enderror" name="item_number" required autocomplete="item_number" placeholder="Item Number">
                                @error('item_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>
                    

                    
                    <div class="input-group mb-3 col-md-3">
                    <select name="sg" class="form-control" required>
                            <option value="1">SG 1</option>
                            <option value="2">SG 2</option>
                            <option value="3">SG 3</option>
                            <option value="4">SG 4</option>
                            <option value="5">SG 5</option>
                            <option value="6">SG 6</option>
                            <option value="7">SG 7</option>
                            <option value="8">SG 8</option>
                            <option value="9">SG 9</option>
                            <option value="10">SG 10</option>
                            <option value="11">SG 11</option>
                            <option value="12">SG 12</option>
                            <option value="13">SG 13</option>
                            <option value="14">SG 14</option>
                            <option value="15">SG 15</option>
                            <option value="16">SG 16</option>
                            <option value="17">SG 17</option>
                            <option value="18">SG 18</option>
                            <option value="19">SG 19</option>
                            <option value="20">SG 20</option>
                            <option value="21">SG 21</option>
                            <option value="22">SG 22</option>
                            <option value="23">SG 23</option>
                            <option value="24">SG 24</option>
                            <option value="25">SG 25</option>
                            <option value="26">SG 26</option>
                            <option value="27">SG 27</option>
                            <option value="28">SG 28</option>
                            <option value="29">SG 29</option>
                            <option value="30">SG 30</option>
                            <option value="31">SG 31</option>
                            <option value="32">SG 32</option>
                            <option value="33">SG 33</option>
                        </select>
                    </div>

                    <div class="input-group mb-3 col-md-3">
                    <select name="stepinc" class="form-control" required>
                            <option value="1">Step 1</option>
                            <option value="2">Step 2</option>
                            <option value="3">Step 3</option>
                            <option value="4">Step 4</option>
                            <option value="5">Step 5</option>
                            <option value="6">Step 6</option>
                            <option value="7">Step 7</option>
                            <option value="8">Step 8</option>
                        </select>
                   </div>

                    </div>


                  

                    <div class="row">
                    <div class="input-group mb-3 col-md-6">
                    <select name="division" class="form-control" required>
                            <option value="Office of the Executive Director (OED)">Office of the Executive Director (OED)</option>
                            <option value="Employment Research Division (ERD)">Employment Research Division (ERD)</option>
                            <option value="Labor and Social Relations Research Division (LSRRD)">Labor and Social Relations Research Division (LSRRD)</option>
                            <option value="Workers Welfare Research Division (WWRD)">Workers Welfare Research Division (WWRD)</option>
                            <option value="Advocacy and Pulications Division (APD)">Advocacy and Pulications Division (APD)</option>
                            <option value="Finance and Administrative Division (FAD)">Finance and Administrative Division (FAD)</option>
                        </select>
                    </div>
                    

                    
                    <div class="input-group mb-3 col-md-6">
                    <input id="unit" type="text" class="form-control @error('unit') is-invalid @enderror" name="unit" required autocomplete="unit" placeholder="Unit">
                                @error('unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>
                    </div>


                    <div class="input-group mb-3">
                        <select name="status" class="form-control" required> 
                            <option value="Active">Active</option>
                            
                        </select>
                    </div>

                    
                    
                    <button class="btn btn-primary shadow-2 mb-4" type="submit"><i class="feather icon-edit"></i> Sign up</button>
                    <p class="mb-0 text-muted">Allready have an account? <a href="login"> Log in</a></p>
                </form>
            </div>
        </div>
    </div>
</div>



