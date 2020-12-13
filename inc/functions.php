<?php
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
?>