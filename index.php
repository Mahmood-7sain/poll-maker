<?php
    session_start();

    try{
        require('connection.php');
        $db->beginTransaction();
        $sql = "SELECT * FROM poll";
        $rows = $db->query($sql);
        $result = $rows->fetchAll(PDO::FETCH_ASSOC);
        if($result){
        $todayDate = date("Y-m-d");
        foreach($result as $det){
            if($todayDate > $det['end_date'] && $det['end_date'] != '0000-00-00'){
                $poll_id = $det['poll_id'];
                $updateSQL = $db -> prepare("UPDATE poll SET poll_status='inactive' WHERE poll_id=?");
                $rs = $updateSQL->execute(array($poll_id));
                if($rs == 0){
                    die("Error updating poll status");
                }
            }
        }
        }else{
            $msgEmpty="No polls are created";
        }
    $db -> commit();
    $db = null;
}
catch(PDOExecption $e){
    $db->rollBack();
    die($e -> getMessage());
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Home Page</title>
</head>

<body>
    <div class='container'>
        <div class="header-container">
            <?php require('header.php'); ?>
            <div class="nav2">
                <form action="" method="POST">
                    <a href="index.php?typepoll=allPolls">All Polls</a>
                    <a href="index.php?typepoll=active">Active Polls</a>
                    <a href="index.php?typepoll=inactive">Inactive Polls</a>
                    <a href="index.php?typepoll=myPolls">My Polls</a>
                </form>
            </div>
        </div>
            
        <main>
            <div class="allpollBoxes">
                <?php
            try{
                require('connection.php');

                if(isset($msgEmpty)){
                    echo '<h3 style="color:white; min-height: 200px">'.$msgEmpty.'</h3>';
                    unset($msgEmpty);
                }else{

                if(isset($_GET['typepoll'])){
                    $poll_type=$_GET['typepoll'];

                    
                    if($poll_type=="active"){
                        $sql = "SELECT * FROM poll WHERE poll_status='active'";
                        $rs = $db->query($sql);
                        $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
                        if($rows){
                        foreach($rows as $k=>$details){
                            extract($details);
                            $sql2 = "SELECT username FROM users WHERE user_id=$user_id";
                            $rs2 = $db->query($sql2);
                            $rows2 = $rs2->fetchAll(PDO::FETCH_ASSOC);
                            
                            echo '
                        <div class="pollBoxes">
                        <a href="view-poll.php?poll_id='.$poll_id.'">
                        <p>Q: '.$question.'</p>
                        <hr>
                        <p class="pollBoxOwner"><b>Poll Owner:</b> '.$rows2[0]['username'].'</p>
                        <p class="pollBoxOwner"><b>Poll Status:</b> <span style="color:green">'. strtoupper($poll_status).'</span></p>
                        </a>
                        </div>
                        ';
                    }
                    }else{
                        echo '<h3 style="color:white; min-height: 200px">There is no active polls</h3>';
                    }
                    
                } else if($poll_type=="inactive"){
                    $sql = "SELECT * FROM poll WHERE poll_status='inactive'";
                    $rs = $db->query($sql);
                    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
                    if($rows){
                    foreach($rows as $k=>$details){
                        extract($details);
                        $sql2 = "SELECT username FROM users WHERE user_id=$user_id";
                        $rs2 = $db->query($sql2);
                        $rows2 = $rs2->fetchAll(PDO::FETCH_ASSOC);
                        
                        echo '
                        <div class="pollBoxes">
                        <a href="view-poll.php?poll_id='.$poll_id.'">
                        <p>Q: '.$question.'</p>
                        <hr>
                        <p class="pollBoxOwner"><b>Poll Owner:</b> '.$rows2[0]['username'].'</p>
                        <p class="pollBoxOwner"><b>Poll Status:</b> <span style="color:red">'. strtoupper($poll_status).'</span></p>
                        </a>
                        </div>
                        ';
                    }
                    }else{
                        echo '<h3 style="color:white; min-height: 200px">There is no inactive polls</h3>';
                    }
                    
                } else if($poll_type=="myPolls"){
                    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){
                        $id = $_SESSION['user'][0];
                        $sql = "SELECT * FROM poll WHERE user_id=$id";
                        $rs = $db->query($sql);
                        $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
                        if(count($rows) == 0){
                            echo "<h3>You don't currently own any polls. Create a poll <span class='myPollLogIn'><a href='create-poll.php'>here</a></span>.</h3>";
                        } else {
                        foreach($rows as $k=>$details){
                            extract($details);
                            $sql2 = "SELECT username FROM users WHERE user_id=$id";
                            $rs2 = $db->query($sql2);
                            $rows2 = $rs2->fetchAll(PDO::FETCH_ASSOC);
                            if($poll_status == 'active'){
                                $stat = "<span style='color:green'>ACTIVE</span>";
                            } else {
                                $stat = "<span style='color:red'>INACTIVE</span>";
                            }
                            echo '
                            <div class="pollBoxes">
                            <a href="view-poll.php?poll_id='.$poll_id.'">
                                <p>Q: '.$question.'</p>
                                <hr>
                                <p class="pollBoxOwner"><b>Poll Owner:</b> '.$rows2[0]['username'].'</p>
                                <p class="pollBoxOwner"><b>Poll Status:</b> '.$stat.'</p>
                                </a>
                                </div>
                                ';
                            }
                        }
                        }else{
                            echo "<h3 style='color: white; min-height: 200px'>Please login to view your polls</h3>";
                        }
                    } else if($poll_type=="allPolls"){
                        $sql = "SELECT * FROM poll";
                        $rs = $db->query($sql);
                        $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
                        foreach($rows as $k=>$details){
                            extract($details);
                            $sql2 = "SELECT username FROM users WHERE user_id=$user_id";
                            $rs2 = $db->query($sql2);
                            $rows2 = $rs2->fetchAll(PDO::FETCH_ASSOC);
                            if($poll_status == 'active'){
                                $stat = "<span style='color:green'>ACTIVE</span>";
                            } else {
                                $stat = "<span style='color:red'>INACTIVE</span>";
                            }
                            
                            echo '
                            <div class="pollBoxes">
                            <a href="view-poll.php?poll_id='.$poll_id.'">
                            <p>Q: '.$question.'</p>
                            <hr>
                            <p class="pollBoxOwner"><b>Poll Owner:</b> '.$rows2[0]['username'].'</p>
                            <p class="pollBoxOwner"><b>Poll Status:</b> '.$stat.'</p>
                            </a>
                            </div>
                            ';
                        }
                    }else{
                        die("400 Bad Request");
                    }
                    }else{
                        $sql = "SELECT * FROM poll";
                        $rs = $db->query($sql);
                        $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
                        foreach($rows as $k=>$details){
                            extract($details);
                            $sql2 = "SELECT username FROM users WHERE user_id=$user_id";
                            $rs2 = $db->query($sql2);
                            $rows2 = $rs2->fetchAll(PDO::FETCH_ASSOC);
                            if($poll_status == 'active'){
                                $stat = "<span style='color:green'>ACTIVE</span>";
                            } else {
                                $stat = "<span style='color:red'>INACTIVE</span>";
                            }
                            
                            echo '
                            <div class="pollBoxes">
                            <a href="view-poll.php?poll_id='.$poll_id.'">
                            <p>Q: '.$question.'</p>
                            <hr>
                            <p class="pollBoxOwner"><b>Poll Owner:</b> '.$rows2[0]['username'].'</p>
                            <p class="pollBoxOwner"><b>Poll Status:</b> '.$stat.'</p>
                            </a>
                            </div>
                            ';
                        }
                    }



                } //end of msgEmpty
                    
                }catch(PDOException $ex){
                die($ex->getMessage());
            }
            ?>
            </div>
        </main>
        <!-- footer  -->
        <?php include('footer.php') ?>
        <!-- end of footer  -->
    </div>

</body>

</html>