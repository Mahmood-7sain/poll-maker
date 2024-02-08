<?php
    session_start();
    // $_SESSION['loggedIn'] = false;
    if(isset($_SESSION['registered'])){
        if($_SESSION['registered']){
            $register="Registered successfully. Please login to continue";
            unset($_SESSION['registered']);
        }
    }

    if(isset($_POST['email'])){
        $email = htmlspecialchars($_POST['email']);
        $pass = htmlspecialchars($_POST['pass']);

        $emailreg = "/^[\w\-]+@([\w-]+\.)+[\w-]{2,}$/i";
        $preg = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*\-_]).{8,}$/";
        if(!preg_match($emailreg,$email)){
            $msgEmail = "*Enter a valid email (Ex: example_123@gmail.com).";
        }

        if(!preg_match($preg,$pass)){
            $msgPass = "*Enter a valid Password";
        }

        if(preg_match($emailreg,$email) && preg_match($preg,$pass)){
            try{
                require('connection.php');
                $sql = $db -> prepare("SELECT * FROM users WHERE email=?");
                $sql ->execute(array($email));
                $row = $sql->fetchAll(PDO::FETCH_ASSOC);
                if(count($row)==0){
                    $noUser = "There is no account registered with that email. Please enter a registered email or continue to ";
                } else {
                    if(count($row)==1){
                        $hashedPass = $row[0]['password'];
                        if(password_verify($pass,$hashedPass)){
                            $_SESSION['loggedIn'] = true;
                            $_SESSION['user'] = [$row[0]['user_id'],$row[0]['username'],$row[0]['email']];
                            header('Location:index.php');
                        } else {
                            $passNoMatch = "Incorrect Password";
                        }
                    } else {
                        die("Error fetching data from database");
                    }
                }
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
    <title>login</title>
</head>

<body>
    <?php
    if(isset($register)){
        echo '<div id="customAlert">
        <p>'. $register.'</p>
        <button onclick="hideCustomAlert()"><img style="width: 25px; cursor: pointer;" src="img/remove.png"></button>
        </div>';
        unset($register);
    }
    ?>
    <div class="logo1">
        <a href="index.php">
            <h1 class="poll">Poll</h1>
            <h2 class="maker">Maker</h2>
        </a>
    </div>

    <div class="container">

        <div class="p-bottom">
            <h1>Login</h1>
            <hr>
        </div>

        <?php 
            if(isset($noUser)){
                echo '
                <div class="alert">
                    <img class="icon-alert" src="img/alert.svg" alt="icon">
                    <span class="msg-alert">'. $noUser.'
                      <a href="signup.php">Create an account</a>
                    </span>
                </div>
                ';
                unset($noUser);
            }
        ?>

        <div class="p-bottom">
            <form method="POST">

                <div class=" input p-top p-bottom">
                    <input class="" type="text" name="email" id="email" onkeyup="checkUN(this.value)"
                        placeholder="Email" value="<?php if(isset($email))echo $email; ?>">
                    <span id="email_ch"><?php if(isset($msgEmail)){echo $msgEmail; unset($msgEmail);}?></span>
                </div>

                <div class=" input p-top p-bottom">
                    <input class="" type="password" name="pass" id="password" placeholder="Password"
                        value="<?php if(isset($pass))echo $pass; ?>">
                    <span>
                        <?php 
                        if(isset($msgPass)){
                            echo $msgPass; unset($msgPass);
                        }elseif(isset($passNoMatch)){
                            echo $passNoMatch; unset($passNoMatch);
                        }
                        ?>
                    </span>
                </div>

                <div class=" input p-top p-bottom p-bt-top">
                    <input class="bt" type="submit" value="Login" id="login">
                </div>

            </form>
        </div>

        <div class="p-bottom">
            <p>Not a Member ? <a href="signup.php">Sign-up</a></p>
        </div>

    </div>

    <script>
    function hideCustomAlert() {
        document.getElementById('customAlert').style.display = 'none';
    }

    function checkUN(str) {
        let emailReg = /^[\w\-]+@([\w-]+\.)+[\w-]{2,}$/i;
        if (!emailReg.test(str)) {
            document.getElementById("email_ch").innerHTML = "*Enter a valid email (Ex: example_123@gmail.com).";
            document.getElementById('email_ch').style.color = "red";
            return;
        }
        const xhttp = new XMLHttpRequest();
        xhttp.onload = myAJAXFunction;
        xhttp.open("GET", "checkemail.php?q=" + str);
        xhttp.send();
    }

    function myAJAXFunction() {
        if (this.responseText == "taken") {
            document.getElementById('email_ch').style.color = "";
            document.getElementById("email_ch").innerHTML = "";
        } else {
            document.getElementById('email_ch').style.color = "red";
            document.getElementById("email_ch").innerHTML = "No user register with this email";
        }
    }
    </script>

</body>

</html>