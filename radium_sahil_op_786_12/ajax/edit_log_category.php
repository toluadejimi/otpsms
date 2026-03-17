<?php
$stt = $_POST['stt'];
$name = $_POST['name'];
$id = $_POST['id'];
include("../auth.php");
if(isset($_SESSION['admin']) =="") {
        
echo"<script>  setTimeout(function(){
            window.location.href = 'index.php';
         }, 100;
</script>"; 
}else{
if($name != "" && $stt != ""){
 
  $sql = "UPDATE categories SET name='$name', stt='$stt' WHERE id='$id'";
  $done=mysqli_query($conn,$sql);
echo'<script>
    $(document).ready(function() {
        Swal.fire({
            title: "Success",
            text: "Details Update Successful",
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