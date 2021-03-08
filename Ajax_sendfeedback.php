<?php  ini_set('max_execution_time', '12000');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start(); 
require_once('includes/smtp.php');
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

error_reporting(1); 
include('includes/PdoConnect.php');
  session_start();
try {
	 $pdo = new PDO($dsn, $user, $password);
$pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
sleep(0);
$response = new stdClass;
date_default_timezone_set('Africa/Cairo');
$t=date("Y-m-d  H:i:s");
$senderfullname=$_POST['names'];
$senderemail=$_POST['email'];
$sendersubject=$_POST['subject'];
$sendermessage=$_POST['message'];
if(isset($_POST['email'])&& !empty($_POST['names'])  && !empty($_POST['subject']) && !empty($_POST['message']))
{
// send email to the cooperative email
$mail = new PHPMailer(true);

try {

    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = $smtphost;  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $smtpuser;                     // SMTP username
    $mail->Password   = $smtppass;                               // SMTP password
    $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom("$senderemail","$senderfullname");
    $mail->addAddress('nshjpaul@gmail.com','jean paul');     // Add a recipient
    $mail->addAddress('info@tnt.rw','TNT Computer consultancy Ltd');     // Add a recipient
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = "$sendersubject";
    $mail->Body    = "$sendermessage";
    $mail->AltBody = 'twongerekawa.rw feedback';

    $mail->send();
   // insert message into feedback table
$resultinsert = $pdo->prepare("INSERT INTO tnt_feedback (feed_usernames,feed_useremail,feedback_subject,feedback,feedback_sentdate) VALUES (
    :feed_usernames,:feed_useremail,:feedback_subject,:feedback,:feedback_sentdate)");
$resultinsert->bindParam(':feed_usernames',$senderfullname);
$resultinsert->bindParam(':feed_useremail',$senderemail);
$resultinsert->bindParam(':feedback_subject',$sendersubject);
$resultinsert->bindParam(':feedback',$sendermessage);
$resultinsert->bindParam(':feedback_sentdate',$t);
$resultinsert->execute();
 // show success result
 $response->result = "Success";
 $response->status = " ";  
 $response->statusb = "Successfull sent";
} catch (Exception $e) {
   $mail->ErrorInfo;
// show error result
 $response->result = "error";
 $response->status = " ";  
 $response->statusb = "Not sent please check your internet connection";
}

}
else{
 // show error field
 $response->result = "empty field";
 $response->status = " ";  
 $response->statusb = "Not sent please check fill all field";   
}
    
  //END 
 die(json_encode($response)); 
	 $DBH = null;
    }
catch(PDOException $e)
    {
     //echo $e->getMessage();
   echo"<font color='red'>No internet connection, connect and </font>&nbsp; <a href=''><i class='fa fa-refresh'></i> Reflesh</a>";
	 
}


?> 