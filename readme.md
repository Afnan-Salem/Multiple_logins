#Run Code

1- Run php artisan migrate in the terminal or use database users.sql 
2- Update .env field for other database/email connections 
3- Database must have roles inserted in roles table

# Test Scenarios

## 1-Register as an orchestra with a valid inputs --> user A

## 2- Check your email to activate your account before login.
Login before verification would redirect to verify account page.

3-logout, Register user A as a musician 
If input fields didn't match the database fields of user A ... a "credentials don't match� message would be displayed

## 3- now user A has 2 roles, select one of them to continue

## 4- When continue as an orchestra, Register new member -->user B

## 5- activate user B account then login.

## 6- logout user B then Register as musician/orchestra
    
## 7-try registering user A as orchestra/musician 




 

