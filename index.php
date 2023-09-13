<?php

//$current_user_id = $_SESSION['user_id'];
$user_id ='12';

?>
<!DOCTYPE html>
<html>
 <head>
  <title></title>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style type="text/css">
   input[type=text] {
      width:50%;
      background-color: white;
      background-image: url('searchicon.png');
      background-position: 10px 10px;
      background-repeat: no-repeat;
     padding-left: 40px;
    }
    .form-group {
        margin-bottom: 10px;
    }

    .form-control {
        width: 50%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: vertical;
    }
  </style>
 </head>
 <body>
  <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
  Comment
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title" id="exampleModalLongTitle" style = "padding-left:25%;">Comment System</h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <br />
          <div class="container">
              <form method="POST" id="comment_form">
                <!--<div class="form-group">
                  <input type="text" name="comment_name" id="comment_name" class="form-control" placeholder="Enter Name" />
                </div>-->
                <div class="form-group">
                  <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Enter Comment" rows="5"></textarea>
                </div>
                <div class="form-group">
                	<!--<input type="hidden" name="comment_id" id="comment_id" value="<?php echo isset($_SESSION['user_id']) ?>" />-->

                    <input type="hidden" name="comment_id" id="comment_id" value="0" />
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>" />
                    <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" />
                </div>
              </form>
            <span id="comment_message"></span>
            <br />
          <div id="display_comment"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>
  </div>
 </body>
</html>

<script>
$(document).ready(function(){
 
 $('#comment_form').on('submit', function(event){
  event.preventDefault();
  var form_data = $(this).serialize();
  $.ajax({
   url:"add_comment.php",
   method:"POST",
   data:form_data,
   dataType:"JSON",
   success:function(data)
   {
    if(data.error != '')
    {
     $('#comment_form')[0].reset();
     $('#comment_message').html(data.error);
     $('#comment_id').val('0');
     load_comment();
    }
   }
  })
 });

load_comment();

 function load_comment()
 {
  $.ajax({
   url:"fetch_comment.php",
   method:"POST",
   success:function(data)
   {
    $('#display_comment').html(data);
   }
  })
 }

$(document).on('click', '.reply', function(){
  var comment_id = $(this).attr("id");
  console.log (comment_id);
  $('#comment_id').val(comment_id);
  $('#comment_name').focus();

  var modalBody = $(this).closest('.modal-content').find('.modal-body');
  modalBody.animate({
    scrollTop: 0
  }, "slow");

 });
 
});
</script>
