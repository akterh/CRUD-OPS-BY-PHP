<?php  require_once( 'inc/functions.php');
$info ='';
$task =$_GET['task'] ?? 'report';
$error =$_GET['error'] ?? '0';
if('seed' == $task){
    seed();
    $info = "seeding is complete";
}

$fname = '';
$lname = '';
$id = '';
if(isset($_POST['submit'])){
    $fname = filter_input(INPUT_POST,'fname',FILTER_SANITIZE_STRING);
    $lname =  filter_input(INPUT_POST,'lname',FILTER_SANITIZE_STRING);
    $id =  filter_input(INPUT_POST,'lname',FILTER_SANITIZE_STRING);
}
if($id){
    //update the existing student
    if($fname != '' && $lname!='' && $id != ''){
        updateStudent($fname,$lname,$id);
        header('location: /index.php?task=report '); 

    }

}else{
    //addd the new student
    if($fname != '' && $lname!='' && $id != ''){
        $result = addStudent($fname,$lname,$id);
        if($result){
            header('location: /index.php?task=report ');
    
        }   else{
            $error = 1;
        }
    }
    

}
if($fname != '' && $lname!='' && $id != ''){
    $result = addStudent($fname,$lname,$id);
    if($result){
        header('location: /index.php?task=report ');

    }   else{
        $error = 1;
    }
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>project</title>
</head>
<body>
    <div class="container" >
        <div class="row ">
            <div class=" col-6 " style="margin-left:400px" >
              <h2>Project-2 CRUD</h2>
              <p>a simple project to perform simple crud operation using plain files and php</p>
              <?php include_once('inc/templates/nav.php');?>
              <hr>
              <?php
              if($info != ''){
                  echo "<p>{$info}</P>";
              }
               
              ?>
              <?php if('1'== $error): ?>
                   
                    <blockquote>DUPLICATE ID</blockquote> 
                <?php endif;  ?>

               <?php if('report' == $task): ?>
            
                      <?php generateReport(); ?>
            
        
                <?php endif; ?>

             <?php if('add'==$task): ?>
                <form action ="/index.php?task=add"  method="POST">
                    <div class="form-group">
                        <label for="fname">first name</label>
                        <input class="form-control mb-3" type="text" name = "fname" id="fname" value="<?php echo $fname ;?>">
                        <label for="lname">last name</label>
                        <input class="form-control mb-3" type="text" name = "lname" id="lname" value="<?php echo $lname ;?>"  >
                        <label for="id">enter id</label>
                        <input class="form-control mb-3" type="number" name = "id" id="id" value="<?php echo $id ;?>" >
                        <button class="btn btn-success" type="submit" name="submit"value="save" >save</button>
                    </div>
                </form>
                <?php endif;?>  
                <?php if('edit'==$task):
                    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_STRING);
                    $student = getStudent($id);
                     if($student):
                       ?>
                         <form action ="/index.php?task=edit"  method="POST">
                            <div class="form-group">
                                <input type="hidden" name="id" value ="<?php echo $id;?>">
                                <label for="fname">first name</label>
                                <input class="form-control mb-3" type="text" name = "fname" id="fname" value="<?php echo $student['fname'] ;?>">
                                <label for="lname">last name</label>
                                <input class="form-control mb-3" type="text" name = "lname" id="lname" value="<?php echo $student['lname']  ;?>"  >
                                <label for="id">enter id</label>
                                <input class="form-control mb-3" type="number" name = "id" id="id" value="<?php echo $student['id']  ;?>" >
                                <button class="btn btn-success" type="submit" name="submit"value="save" >Update</button>
                            </div>
                        </form>
                     <?php 
                    endif;
                 endif ;
                
                ?>      
              
             
             
               
            </div>
        </div >
       
        

         
    </div>    
    <script src="js/jquery-slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script> 
</body>
</html>

 