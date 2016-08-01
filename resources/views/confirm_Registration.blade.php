Hi {{$fname}} {{$surname}}, Welcome to our website.<br>
<?php Session::set('activation_code', str_random(20));?>
Please click <a href="{{url('verify/'.session()->get('activation_code').'') }}"> Here</a> to activate your account. <br>