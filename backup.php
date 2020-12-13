
//function

<?php
//function part
define('DB','\project_php\projects\crud\inc/db.txt');
function seed(){
    $data = [
        [
            'fname' => 'palash',
            'lname' => 'kebla',
            'id' => '02'
        ],
        [
            'fname' => 'fahad',
            'lname' => 'kala',
            'id' => '14'
        ],
        [
            'fname' => 'zihan',
            'lname' => 'lesbos',
            'id' => '17'
        ],
        [
            'fname' => 'sajib',
            'lname' => 'bahu',
            'id' => '06'
        ],
        [
            'fname' => 'akter',
            'lname' => 'hossain',
            'id' => '91'
        ]
     ];
     $serializedData = serialize($data);
     file_put_contents(DB,$serializedData,LOCK_EX);
}
function generateReport(){
    $serializedData = file_get_contents(DB);
    $students  = unserialize($serializedData);

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
    <table class ="table table-striped">
       <tr class ="table-row">
         <th class ="table-header">name</th>
         <th class ="table-header">id</th>
         <th class ="table-header">action</th>
       </tr>
    <?php foreach($students as $student){ 
            ?>
            <tr>
                 <td><?php printf('%s %s',$student['fname'],$student['lname']);?> </td>               
                 <td><?php printf('%s ',$student['id']);?> </td>               
                 <td><?php printf('<a href ="/index.php?task=edit&id=%s" >Edit </a> | <a href = "/index.php?task=delete&id=%s" >delete</a>',$student['id'],$student['id']);?> </td>               
            </tr>
           
           <?php  } 
        
        ?>    
    </table>
        
    </body>
    </html>
    
    <?php
}

function addStudent($fname,$lname,$id){
    $found = false;
    $serializedData = file_get_contents(DB);
    $students  = unserialize($serializedData);
    foreach($students as $_student){
        if($_student['id'] == $id){
            $found =true;
            break;
        }
        

    }
    
    if(!$found){
    $newId = count($students)+1;    
    $student =[
        'fname' => $fname,
        'lname' =>$lname,
        'id'  => $newId
    ];
    array_push($students,$student);
    $serializedData = serialize($students);
     file_put_contents(DB,$serializedData,LOCK_EX);
     return true;
    }return false;


} 
function getStudent($id){
    $serializedData = file_get_contents(DB);
    $students  = unserialize($serializedData);
    foreach($students as $student){
        if($student['id'] == $id){
            return $student;
            
        }  
}return false;

}
function updateStudent($fname,$lname,$id){
    $found = false;
    foreach($students as $_student){
        if($_student['id'] == $id){
            $found =true;
            break;
        }
        

    }
    if(!$found){
    $serializedData = file_get_contents(DB);
    $students  = unserialize($serializedData);
    $students[$id-1] ['fname'] = $fname;
    $students[$id-1] ['lname'] = $lname;
    $students[$id-1] ['id'] = $id;
    $serializedData = serialize($students);
    file_put_contents(DB,$serializedData,LOCK_EX);
    return true;

}
return false;

}




?>





<?php  require_once( 'inc/functions.php');

//index part


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
        $result = updateStudent($fname,$lname,$id);
        if($result){
            header('location: /index.php?task=report ');
    
        }   else{
            $error = 1;
        } 

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
                         <form   method="POST">
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

 