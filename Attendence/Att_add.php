<html><head><title>Student Attendece</title><style>
table{ width:50%;}
table,th,td {text-align:center;    border:1px solid gray;    border-collapse: collapse;    height:30px;    }

</style></head><body><?php require_once 'menubar.php'; require_once 'dbvariables.php';require_once 'Sanitize.php';?><center>

<form method='post' action='Att_add.php'><table border=1>
<tr><td>Class:</td><td><select name='class'>
<option value='11TH'>11TH</option><option value='12TH'>12TH</option>
</select></td><td>Division:</td><td><select name='division'><?php
for($i=1;$i<11;$i++)
echo "<option value='S$i' selected>S$i</option>";
?></select></td><td><input type='submit' value='Show'></td></tr></table></form><br>
<?php
if(isset($_POST['class']) && isset($_POST['division'])){
    $class=Sanitize::sanitizeMySQL($conn, 'class');$division=Sanitize::sanitizeMySQL($conn, 'division');
    $query="SELECT Roll_Number FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' ORDER BY Roll_Number";
    $result_1 = $conn->query($query);    if (!$result_1) die ("Database access failed: " . $conn->error);
    $rows = $result_1->num_rows;
?>
<form method='post' action='Att_add.php'><table><tr>
<td>Date:</td><td><input type='date' name='date'></td>
<td>Status:</td><td><select name='status'><option value='Absent'>Absent</option><option value='Present'>Present</option></select></td></tr>
<input type='hidden' name='class' value='<?php echo $class;?>'>
<input type='hidden' name='division' value='<?php echo $division;?>'>
</table><br>
<table border=1><tr><td>Enter Roll Numbers:</td><td><input type='text' size=50 name='rollno' value='1,2,3,4,5'></td></tr></table>
<br><center>OR</center><br><table style='width:20%;'><caption>Select Roll Numbers:</caption>
<?php
for($i=0;$i<$rows;$i++){
    $result_1->data_seek($i);    $row = $result_1->fetch_array(MYSQLI_NUM);
    echo "<tr><td><label>$row[0]<input type='checkbox' name='rollno[]' value='$row[0]' style='font-size:25pt'></label></td></tr>";    
}
?>
</table><br><table>
<tr><td><input type='submit' value='Add Attendence'></td></tr>
</table></form></center>
<?php
}
if(isset($_POST['class']) &&isset($_POST['division']) && isset($_POST['date']) && isset($_POST['status'])){
    $class=Sanitize::sanitizeMySQL($conn, 'class');    $division=Sanitize::sanitizeMySQL($conn, 'division');    $date=Sanitize::sanitizeMySQL($conn, 'date');     
    $status=Sanitize::sanitizeMySQL($conn, 'status');    
    if($date!=''){
        $date=explode('-',$date);
        switch($date[1]){
            case '01': $s='jan';break;case '02': $s='feb';break;case '03': $s='mar';break;case '04': $s='apr';break;case '05': $s='may';break;case '06': $s='jun';break;
            case '07': $s='jul';break;case '08': $s='aug';break;case '09': $s='sep';break;case '10': $s='oct';break;case '11': $s='nov';break;case '12': $s='dec';break;
        }
        echo "$date[2]-$s-$date[0]<br>";    
        echo "$s$date[0]:$date[2]<br>";//mmyyyy:dd
        switch($date[2]){
            case '01':$date[2]='1';break;case '02':$date[2]='2';break;case '03':$date[2]='3';break;case '04':$date[2]='4';break;case '05':$date[2]='5';break;
            case '06':$date[2]='6';break;case '07':$date[2]='7';break;case '08':$date[2]='8';break;case '09':$date[2]='9';break;
        }    
    }else die("Select date");

    if(isset($_POST['rollno'])){
        $rollno=$_POST['rollno'];        
        if($rollno!=NULL){
            $month="$s$date[0]";   $day="d$date[2]";       
            if($status=='Present'){
                addAtt($conn,'Absent',$month,$day,$class,$division);        
                echo $status.":";
                foreach($rollno as $r){
                    echo (int)substr($r,strlen($r)-2,strlen($r)).",";                
                    $query="UPDATE $month SET $day='P' WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Roll_Number='$r')";                       
                    $result = $conn->query($query); if (!$result) die ("Database access failed: " . $conn->error);                    
                }
            }else{
                addAtt($conn,'Present',$month,$day,$class,$division);        
                echo $status.":";
                foreach($rollno as $r){
                    echo (int)substr($r,strlen($r)-2,strlen($r)).",";                
                    $query="UPDATE $month SET $day='A' WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Roll_Number='$r')";                       
                    $result = $conn->query($query); if (!$result) die ("Database access failed: " . $conn->error);                    
                }
            }
        }
   }else{
       $month="$s$date[0]";   $day="d$date[2]";
       addAtt($conn,$status,$month,$day,$class,$division);        
    }    
    echo "<br>Attendence ADDED successfully!!!";
}
function addAtt($conn,$status,$month,$day,$class,$division){
    if($status == 'Present') $pa='P'; else  $pa='A';
    $query="UPDATE $month SET $day='$pa' WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division')";
    $result_ = $conn->query($query); if (!$result_) die ("Database access failed: " . $conn->error);        
}
?>
</body></html>