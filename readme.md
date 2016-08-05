#Run Code

1- Run php artisan migrate in the terminal or use database users.sql

2- Update .env field for other database/email connections.

3- Run php artisan migrate, php artisan  db:seed


4- when updating/ installing composer some files may be overwritten, check the following:

    #...\vendor\laravel\framework\src\Illuminate\Foundation\Auth\ResetsPasswords.php
          protected function resetEmailBuilder()
             {
                 return function (Message $message) {
                     $message->subject($this->getEmailSubject());
                     $message->from('yourmail@email.com', 'title');
                 };
             }

    #...\vendor\laravel\framework\src\Illuminate\Auth\Events\Logout.php
           public function __construct($user)
              {
                  $this->user = $user;
                  session()->forget('curr_role');
              }

# Test Scenarios

1-Register as an orchestra with a valid inputs --> user A

2- Check your email to activate your account before login.
Login before verification would redirect to verify account page.

3-logout, Register user A as a musician
If input fields didn't match the database fields of user A ... a "credentials don't match” message would be displayed

4- now user A has 2 roles, select one of them to continue

5- When continue as an orchestra, Register new member -->user B

6- activate user B account using different browser or logout user A then activate user B.

7- logout user B then Register as musician/orchestra

8- try registering user A as orchestra/musician

9- try forget password method

#You may run into this error messages

1- TokenMismatchException

   simulate login.blade line 11 
     <input type="hidden" name="_token" value="{{ csrf_token() }}">

2- Swift_TransportException

   override PasswordController.php, Add
    protected function resetEmailBuilder()
       {
           return function (Message $message) {
               $message->subject($this->getEmailSubject());
               $message->from('yourmail@email.com', 'title');
           };
       }




