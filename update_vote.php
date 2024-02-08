<?php
session_start();

if(isset($_POST['options'])){
    try {
        require('connection.php');
        $db->beginTransaction();
        $user_id=$_SESSION['user'][0];
        $answer_id=intval($_POST['options']);
        if(!isset($_GET['poll_id'])){
            die("400 BAD REQUEST");
        } else {
            $poll_id=$_GET['poll_id'];
        }

        $sql3 = $db -> prepare("UPDATE poll SET total_votes=total_votes+1 WHERE poll_id=?");
            $rs = $sql3 ->execute(array($poll_id));
            if($rs != 1){
                die("Error updating poll");
            } else {
                $sql2= $db -> prepare("UPDATE answers SET num_votes=num_votes+1 WHERE answer_id=?");
                $rs2=$sql2->execute(array($answer_id));
                if($rs2!=1){
                    die("Error in inserting into answers");
                 } else {
                    $sql= $db -> prepare("INSERT INTO voted VALUES(:vid, :usrid, :ansid, :polid)");
                    $sql -> bindValue(':vid','');
                    $sql -> bindParam(':usrid',$user_id);
                    $sql -> bindParam(':ansid',$answer_id);
                    $sql -> bindParam(':polid',$poll_id);
                    $rs3=$sql->execute();
                    if($rs3 != 1){
                        die("Error in insertion: voted");
                    }
                 }
            }
        $db->commit();
        $db=null;
        header('Location:view-poll.php?poll_id='.$poll_id);
    } catch (PDOException $ex) {
        $db->rollBack();
        die("Error: ".$ex->getMessage());
    }
    } else {
        if(!isset($_GET['poll_id'])){
            die("400 BAD REQUEST");
        } else {
            $poll_id=$_GET['poll_id'];
            header('Location:view-poll.php?poll_id='.$poll_id. '&empt=true');
        }
    }
?>