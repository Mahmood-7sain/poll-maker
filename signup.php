<?php
//Start a session to store whether the user registered or not
session_start();
$_SESSION['registered'] = false; //Set session value 'registered' of user to be false as the user was redirected to the page

    //Check form submition
    if(isset($_POST['username'])){
        //Save form input using htmlspecialchars to avoid injection attacks
        $name = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $pass = htmlspecialchars($_POST['pass']);
        $cpass = htmlspecialchars($_POST['conPass']);

        //Trim name input to remove spaces
        $name = trim($name, " ");

        //Validate name, email, and password using regexp and set appropriate error msgs

        //Only letters allowed in the name; First and last name required
        $nameReg = "/^[a-z]+(\s[a-z]+)?$/i";
        if(!preg_match($nameReg, $name)){
            $msgName = "*Enter a valid first and last name (Ex: Ali Mohd).";
        }

        $emailreg = "/^[\w\-]+@([\w-]+\.)+[\w-]{2,}$/i";
        if(!preg_match($emailreg,$email)){
            $msgEmail = "*Enter a valid email (Ex: example_123@gmail.com).";
        }

        $preg = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*\-_]).{8,}$/";
        if(!preg_match($preg,$pass)){
            $msgPass = "*Password must contain at least one uppercase letter, lowercase letter, digit, special character, and (minimum 8 characters).";
        }

        if($pass != $cpass){
            $msgCP = "*Password do not match.";
        }

        // Check validity of input
        if(preg_match($nameReg,$name) && preg_match($emailreg,$email) && preg_match($preg,$pass) && $pass == $cpass){
            try{
                //Connect to the DB
                require('connection.php');

                //Check if user already exists in the DB and return error msg
                $sql = $db -> prepare("SELECT * FROM users WHERE email=?");
                $sql -> execute(array($email));
                $row = $sql->fetchAll();
                if(!empty($row)){
                    $msgRegister = "*Failed to register. Email already taken.";
                } else {
                    //If user not taken hash the password and insert the user to the table
                    $hashedPass = password_hash($pass,PASSWORD_DEFAULT);
                    $addSql = "INSERT INTO users VALUES('','$name','$email', '$hashedPass','')";
                    $rows = $db->exec($addSql);
                    if($rows == 1){
                        //Set session value 'registered' to be true as user has succesfully registered and redirect to login page
                        $_SESSION['registered'] = true;
                        header("Location:login.php");
                    }
                }
                //Close DB connection
                $db = null;
            }
            catch(PDOExecption $e){
                die($e -> getMessage());
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signup-login.css">
    <title>signup</title>
</head>

<body>
    <div class="logo1">
        <a href="index.php" class="toIndex">
            <h1 class="poll">Poll</h1>
            <h2 class="maker">Maker</h2>
        </a>
        </div>
    <div class="container">
        <div class="p-bottom">
            <h1>Sign-up</h1>
            <hr>
        </div>
        <div class="p-bottom">
            <form action="" method="POST">
                <div class=" input p-top p-bottom">
                    <input class="" type="text" id="email" name="username" placeholder="Username" 
                    value=<?php if(isset($name))echo $name; ?>>
                    <span><?php if(isset($msgName)){echo $msgName; unset($msgName);}?></span>
                </div>
                <div class=" input p-top p-bottom">
                    <input class="" type="text" id="email" name="email" onkeyup="checkUN(this.value)" placeholder="Email"
                    value=<?php if(isset($email))echo $email; ?>>
                    <span id="emailcheck">
                        <?php 
                        if(isset($msgEmail)){
                            echo $msgEmail; 
                            unset($msgEmail);
                        }elseif(isset($msgRegister)){
                            echo $msgRegister;
                            unset($msgRegister);
                        }
                        ?>
                    </span>
                </div>
                <div class=" input p-top p-bottom">
                    <input class="" type="password" id="password" name="pass" placeholder="Password"
                    value=<?php if(isset($pass))echo $pass; ?>>
                    <span id="check_pass"><?php if(isset($msgPass)){echo $msgPass; unset($msgPass);}?></span>
                </div>
                <div class=" input p-top p-bottom">
                    <input class="" type="password" id="password" name="conPass" placeholder="Confirm password"
                    value=<?php if(isset($cpass))echo $cpass;?>>
                    <span><?php if(isset($msgCP)){echo $msgCP; unset($msgCP);}?></span>
                </div>

                <div class=" input p-top p-bottom p-bt-top">
                    <input type="submit" class="bt" value="Sign Up" id="login">
                </div>
            </form>
        </div>
        <div class="p-bottom">
            <p>Already have an account ? <a href="login.php">Login</a></p>
        </div>
    </div>
    <script>

function checkUN(str) {
    let emailReg = /^[\w\-]+@([\w-]+\.)+[\w-]{2,}$/i;
  if (!emailReg.test(str)) {
    document.getElementById("emailcheck").innerHTML = "*Enter a valid email (Ex: example_123@gmail.com).";
    document.getElementById('emailcheck').style.color="red";
    return;
  }
  const xhttp = new XMLHttpRequest();
  xhttp.onload = myAJAXFunction;
  xhttp.open("GET", "checkemail.php?q="+str);
  xhttp.send();
}

function myAJAXFunction(){
  if (this.responseText=="taken"){
    document.getElementById('emailcheck').style.color="red";
    document.getElementById("emailcheck").innerHTML = "Email already taken, try another one";
  }
  else {
    document.getElementById('emailcheck').style.color="";
    document.getElementById("emailcheck").innerHTML = "";
  }
}
</script>
</body>

</html>