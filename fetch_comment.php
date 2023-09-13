<?php

//fetch_comment.php

$connect = new PDO('mysql:host=localhost;dbname=verify_db', 'root', ''); ///replace with appropriate database name
$userid =12; //replace with session id

$query = "SELECT * FROM comment_tabel WHERE parent_comment_id = '0' ORDER BY comment_id DESC";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '';
foreach($result as $row)
{
 $output .= '
 <div class="panel panel-default" style= "width:30%;" >
  <div class="panel-heading">By <b>'.$row["comment_sender_name"].'</b> on <i>'.$row["date"].'</i></div>
  <div class="panel-body">'.$row["comment"].'</div>';


  // Check if parent_comment_id is not equal to 0
    if ($row["user_id"] != $userid) {
        $output .= '<div class="panel-footer" align="right"><button type="button" class="btn btn-default reply" id=" '.$row["comment_id"].'"  style="border: 1px solid #333;">Reply</button></div>';
    }
    $output .= '<hr style="border: 1px solid #333;">';
    $output .= '</div>';
 $output .= get_reply_comment($connect, $row["comment_id"]);
}

echo $output;

function get_reply_comment($connect, $parent_id = 0, $marginleft = 0)
{
$userid =12; // session user id can be initialized here or passed as argument from above function
 $query = "
 SELECT * FROM comment_tabel WHERE parent_comment_id = '".$parent_id."'
 ";
 $output = '';
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 $count = $statement->rowCount();
 if($parent_id == 0){
    $marginleft = 0;
  }else{
      $marginleft = $marginleft + 48;
}


 if($count > 0)
 {
  foreach($result as $row)
  {
   $output .= '
   <div class="panel panel-default" style="margin-left:'.$marginleft.'px;width:30%;">';

    // Check if parent_comment_id is not equal to 0
    //if ($row["parent_comment_id"] != 0) {
        // Fetch the name of the parent comment
        $parentCommentName = get_parent_comment_name($connect, $row["parent_comment_id"]);
        $output .= '<div class="panel-heading"><b><i>(Replying to: '.$parentCommentName.')</i> By '.$row["comment_sender_name"].'</b> on <i>'.$row["date"].'</i>';

    $output .= '<div class="panel-body">'.$row["comment"].'</div>';

    if ($row["user_id"] != $userid) {
        $output .= '<div class="panel-footer" align="right"><button type="button" class="btn btn-default reply" id=" '.$row["comment_id"].'"  style="border: 1px solid #333;" >Reply</button></div>';
    }

    $output .= '</div>';
   $output .= get_reply_comment($connect, $row["comment_id"], $marginleft);
  }
 }
 return $output;
}
function get_parent_comment_name($connect, $parent_id) {
  $query = "SELECT comment_sender_name FROM tbl_comment WHERE comment_id = '".$parent_id."'";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchColumn();
  return $result;
}

?>