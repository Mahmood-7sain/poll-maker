<?php
session_start();
    if(isset($_POST['submit'])){
        $question=htmlspecialchars($_POST['question']);
        //$_POST['options'] is an array of choices
        $choice=$_POST['options'];
        //choice[];
        if(!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']){
            $msgNotLoggedIn= "Please login to create poll";
        }else{
            //check the question, if the user input spaces instead of text than print msg
            if(trim($_POST['question'])==""){
                $msgEmptyQuestion=" *Question input can't be empty";
            }

            //check the options, if the user input spaces instead of text than print msg
            foreach($_POST['options'] as $value){
                if(trim($value)==''){
                    $msgEmptyChoice=" *please fill your options";
                }
            }

            if(!isset($msgEmptyChoice) && !isset($msgEmptyQuestion)){
                try{
                    require('connection.php');

                    $db->beginTransaction();

                    $user_id=$_SESSION['user'][0];
                    $currentDate= date('Y-m-d');
                    $endDate=$_POST['end_date'];
                    $rs=false;

                    //if the user schedulle a date:
                    if(isset($_POST['end_date']) && $_POST['end_date']!=''){
                        //the due date should be in the future not past, ckeck if the end date greater then current date for validation:
                        if($_POST['end_date']>$currentDate){
                            $sql_with_date="INSERT INTO poll VALUES(null, ?, ?, ?, ?, NOW(), ?)";
                            $stmt= $db->prepare($sql_with_date);
                            $rs = $stmt->execute([$question ,0 ,$user_id ,'active' ,$endDate]);
                        }else{
                            $msgInvalidDate=" *Enter a valid due date";
                        }
                    }else{
                        $sql_without_date="INSERT INTO poll VALUES(null, ?, ?, ?, ?, ?, ?)";
                        $stmt= $db->prepare($sql_without_date);
                        $rs = $stmt->execute([$question ,0 ,$user_id ,'active' ,'' ,'']);
                    }

                    //if $rs==true
                    if($rs){
                        //once we insert the question, we take his id directly by using lastInsertId()  function:
                        $question_id=$db->lastInsertId();
                        $sql2= "INSERT INTO answers VALUES(null, ?, ?, ?)";
                        $stmt2= $db->prepare($sql2);
                        foreach($choice as $value){
                            $rs2 = $stmt2->execute([$value ,0 , $question_id]);
                            if($rs2 != 1){
                               $msgError = "Failed";
                               break;
                            }
                        }
                        if(!isset($msgError)){
                            header('Location:view-poll.php?poll_id='.$question_id.'&pp=true');
                        }
                    }

                    $db->commit();
                    $db=null;
                }catch(PDOExecption $ex){
                    $db->rollBack();
                    die("Error: ". $ex->getMessage());
                }
            }
        }
    }
    if(isset($_POST['cancel'])){
        header("Location:index.php");
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/create-poll.css">
    <title>Create Poll</title>
</head>

<body>
    <div class="container" id="con">
        <?php
         require('header.php'); 
         ?>
        <?php
            if(isset($msgNotLoggedIn)){
                echo '<div id="customAlert">
                <p>'. $msgNotLoggedIn.' <a href="login.php">click here</a></p>
                <button onclick="hideCustomAlert()"><img style="width: 25px; cursor: pointer;" src="img/remove.png"></button>
                </div>';
                unset($msgNotLoggedIn);
            }
        ?>
            <form method="POST">
                <div class="bx">
                    <label for="question" class="m-bottom">Title/Question</label>
                    <div class="myInput">
                        <input type="text" class="input-text" name="question" id="question"
                        placeholder="Enter your question" value=<?php if(isset($question))echo $question;?>>
                    </div>
                    <span class="spanmsg"><?php if(isset($msgEmptyQuestion)){echo $msgEmptyQuestion; unset($msgEmptyQuestion);}?></span>
                </div>
                
                <div class="bx">
                    <div id="options-container">
                        <label for="options" class="m-bottom">Options</label> <span class="spanmsg"
                        id="sp1"><?php if(isset($msgEmptyChoice)){echo $msgEmptyChoice; unset($msgEmptyChoice);}?></span><br>
                        <div class="option-container myInput m-bottom">
                            <input type="text" id="inpt_Opt" class="input-text" name="options[]" placeholder="Option"
                            value=<?php if(isset($choice))echo $choice[0];?>>
                            <button type="button" onclick="removeOption(this)"><img src="img/remove.png"></button>
                        </div>
                        <div class="option-container myInput m-bottom">
                            <input type="text" id="inpt_Opt" class="input-text" name="options[]" placeholder="Option"
                            value=<?php if(isset($choice))echo $choice[1];?>>
                            <button type="button" onclick="removeOption(this)"><img src="img/remove.png"></button>
                        </div>
                    </div>
                    <button class="addOption" type="button" onclick="addOption()">Add Option</button>
                </div>
                
                <div class="end-date-optional bx">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" id="end_date" placeholder="YYYY-MM-DD"> <span class="spanmsg"
                    id="sp2"><?php if(isset($msgInvalidDate)){echo $msgInvalidDate; unset($msgInvalidDate);}?></span>
                    <small>Optional, either you can schedule a date to closs your poll or you can manually end it after creating it.</small>
                </div>
                
                <div class="bx">
                    <input type="submit" class="sbt" name="submit" value="Submit">
                    <input type="submit" class="sbt" name="cancel" value="Cancel">
                </div>
            </form>
        </div>
        
    <script>
    let c = 2;

    function addOption() {
        if(c < 6){
            ++c;
            const optionsContainer = document.getElementById('options-container');
            const newOption = document.createElement('div');
            newOption.className = 'option-container myInput m-bottom';
            newOption.innerHTML = '<input type="text" class="input-text" name="options[]" placeholder="Option" >' +
            '<button type="button" class="remove-option" onclick="removeOption(this)"><img src="img/remove.png"></button>';
            optionsContainer.appendChild(newOption);
        }
    }

    function removeOption(button) {
        if (c > 2) {
            const optionsContainer = document.getElementById('options-container');
            const optionContainer = button.parentNode;
            optionsContainer.removeChild(optionContainer);
            --c;
        }
    }
    
    function hideCustomAlert() {
        document.getElementById('customAlert').style.display = 'none';
    }
    </script>
</body>

</html>