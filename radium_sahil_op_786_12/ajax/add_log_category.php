<?php
session_start();
$stt = $_POST['stt'];
$category_name = $_POST['name'];
   
include("../auth.php");
if(isset($_SESSION['admin']) =="") {
        
echo"<script>  setTimeout(function(){
            window.location.href = 'index.php';
         }, 100;
</script>"; 
}else{
if($category_name != ""){
  $sql = "INSERT INTO categories (stt, name, status) VALUES ('$stt', '$category_name', '1')";
  $done=mysqli_query($conn,$sql);
echo'<script>
    $(document).ready(function() {
        Swal.fire({
            title: "Success",
            text: "Details Added Successful",
            icon: "success",
            button: "Ok",
            
        });
    });
</script>';  
}else{
echo'<script>
    $(document).ready(function() {
        Swal.fire({
            title: "Warning!",
            text: "Please fill all the fields first",
            icon: "warning",
            button: "Ok",
            
        });
    });
</script>';   
}
}
?>