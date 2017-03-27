<!DOCTYPE html>
<?php
session_start();
include 'connect.php';

if(array_key_exists("Logout", $_GET)) {
    
//    unset($_SESSION);
    session_destroy();
    setcookie("userIn", "", time() - 60 *60); //using this so I can work on the page - remove a cookie
    $_COOKIE['userIn'] = "";
    
}elseif (isset($_COOKIE['userIn'])) {
    
 header("Location: session.php"); //Normally you would issue this redirect
    
}

$email = "";

$password = "";

$error = "";



if (isset($_POST['submit'])) {
    
    if ($_POST['email'] == ''){ 
        
        $error .= "Email is required<br>";
        
    } elseif ($_POST['password'] == '') {
        
        $error .= "Password is required<br>";
    
    } else {

        $email = $_POST['email'];
        
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);        

        $query = "SELECT * FROM users WHERE `email` = '" . mysqli_real_escape_string($link, $email) . "'";  //strips out nasties

        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) != 0) {

            $error = 'Sorry! That email already exists';

        } else {

            $query = "INSERT INTO `users` (`email`, `password`) VALUES('" . mysqli_real_escape_string($link, $email) . "', '" . mysqli_real_escape_string($link, $password) . "')";

            if ($result = mysqli_query($link, $query)) {

                $_SESSION['id'] = $_POST['email'];
                
                if(isset($_POST['stayIN'])) {
                    
                    setcookie("userIn", $_POST['email'], time() + 60 * 60 * 24 * 5); //set a cookie for 5 days
                    
                }
                
                header("Location: session.php");

            } else {

                $error = 'I\'m sorry, there was an error - try again later.';

            }

        }
    }
} 
if (isset($_POST['submitLI'])) {
    
    if ($_POST['emailLI'] == '') {
        
        $error .= "Email is required<br>";
        
    }elseif ($_POST['passwordLI'] == '') {
        
        $error .= "Password is required<br>";
        
    } else {


        if (mysqli_connect_error()) {

            die ("There was an issue trying to connect to the database");

        } 

        $emailLI = $_POST['emailLI'];

        $passwordLI = $_POST['passwordLI'];
        
        $query = "SELECT email, password FROM users WHERE `email` = '" . mysqli_real_escape_string($link, $emailLI) . "'";  //strips out nasties

        $result = mysqli_query($link, $query);
        
        if(mysqli_num_rows($result) == 0) {
            
            $error = "I'm sorry, that username/password combination was not found.";
            
        }else {
        
            while ($row = mysqli_fetch_array($result)) {


                if ($row['email'] == $emailLI && password_verify($passwordLI, $row['password'])) {

                        $_SESSION['id'] = $_POST['emailLI'];

                        if(isset($_POST['stayINLI'])) {

                            setcookie("userIn", $_POST['emailLI'], time() + 60 * 60 * 24 * 5); //set a cookie for 5 days

                        }

                        header("Location: session.php");

                } else {

                    $error = 'Sorry! Your password is incorrect!';

                }
            }
        }
        
    }
    
    $link->close();

}

?>



<?php include 'header.php'; ?>
      
    <div class="container homePageContainer">
      
        <h1>Secret Diary</h1>
        
        <?php
        
        if($error){
        
            echo '<div class="alert alert-danger" role="alert" id="error">' . $error . '</div>';
            
        }
        ?>

        <form method="post" id="signUp">
            <fieldset class="form-group">
            
            <div class="form-group">
            
                <label class="lblLeft" for="email">Your Email</label>
                
                <input type="email" class="form-control" name="email" id="email" placeholer="me@me.com" />

            </div>    

            <div class="form-group">

                <label class="lblLeft" for="password">Your Password</label>

                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            
            </div>
            
            <div class="form-check">

                <label class="form-check-label" for="stayIN">

                <input type="checkbox" class="form-check-input" id="stayIN" name="stayIN"> Stay checked in
                
                </label>
            
            </div>

            <input type="submit" class="btn btn-primary" name="submit" value="Sign Up!">
                
            </fieldset>
        </form>
        <form class="hide" method="post" id="logIn">
            <fieldset class="form-group">    

            <div class="form-group">
                
                <label class="lblLeft" for="emailLI">Your Email</label>

                <input type="email" class="form-control" id="emailLI" name="emailLI">
                
            </div>
                
            <div class="form-group">
                <label class="lblLeft" for="passwordLI">Your Password</label>

                <input type="password" class="form-control" id="passwordLI" name="passwordLI" id="password" placeholder="Password">
                
            </div>
                
            <div class="form-check">
            
                <label class="form-check-label" for="stayINLI">

                <input type="checkbox" class="form-check-input" id="stayINLI" name="stayINLI"> Stay checked in
                
                </label>
                
            </div>

                <input type="submit" class="btn btn-primary" name="submitLI" value="Login!!">
                
            </fieldset>    
        </form>
            <p><a id="showLogin">Login</a></p>
        
    
    
    </div>
<?php include 'footer.php'; ?>