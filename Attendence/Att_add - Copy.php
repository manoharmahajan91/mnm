<html><head><title>Student Attendece</title><style>
table, th, td {
    text-align:left;
    border:1px solid gray;
    border-collapse: collapse;
    height:30px;    
    }
th{
    text-align:center;
    width:25px;
}
</style></head><body><?php require_once 'menubar.php'; require_once 'dbvariables.php';require_once 'Sanitize.php';?><center><form method='post' action='Att_add.php' name='form1'><table border=1>
<tr><td>Class:</td><td><select name='class'>
<option value='11TH'>11TH</option>
<option value='12TH'>12TH</option>
</select></td></tr>
<tr><td>Division:</td><td><select name='division'><?php
for($i=1;$i<11;$i++)
echo "<option value='S$i' selected>S$i</option>";
?></select></td></tr>
<tr><td>Date:</td><td><input type='date' name='date' value='2018-01-01'></td></tr>
<tr><td>Roll Numbers:</td><td><input type='text' name='rollno' value='1,2,3,4,5'></td></tr>
<tr><td>Status:</td><td><select name='status'><option value='present'>Present</option><option value='absent'>Absent</option></select></td></tr>
<tr><td colspan=2><center><input type='submit' value='Add Attendence'></center></td></tr>
</table></form></center>
<?php

if(isset($_POST['class']) &&isset($_POST['division']) && isset($_POST['date']) && isset($_POST['rollno']) && isset($_POST['status'])){
    $class=Sanitize::sanitizeMySQL($conn, 'class');$division=Sanitize::sanitizeMySQL($conn, 'division');$date=Sanitize::sanitizeMySQL($conn, 'date');    $rollno=Sanitize::sanitizeMySQL($conn, 'rollno');    $status=Sanitize::sanitizeMySQL($conn, 'status');    
    
   
    
    $date=explode('-',$date);
    switch($date[1]){
        case '01': $s='jan';break;case '02': $s='feb';break;case '03': $s='mar';break;case '04': $s='apr';break;case '05': $s='may';break;case '06': $s='jun';break;
        case '07': $s='jul';break;case '08': $s='aug';break;case '09': $s='sep';break;case '10': $s='oct';break;case '11': $s='nov';break;case '12': $s='dec';break;
    }
    echo "$date[2]-$s-$date[0]<br>";    
    echo "$s$date[0]:$date[2]";//mmyyyy:dd
    switch($date[2]){
        case '01':$date[2]='1';break;case '02':$date[2]='2';break;case '03':$date[2]='3';break;case '04':$date[2]='4';break;case '05':$date[2]='5';break;
        case '06':$date[2]='6';break;case '07':$date[2]='7';break;case '08':$date[2]='8';break;case '09':$date[2]='9';break;
    }    
    if(strpos($rollno,',')>0){
        $rollno=explode(',',$rollno);
    }else if(strpos($rollno,' ')>0){
        $rollno=explode(' ',$rollno);
    }
    
    $query="SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division'";
    $result_1 = $conn->query($query);    if (!$result_1) die ("Database access failed: " . $conn->error);
    $rows = $result_1->num_rows;
    
    if($status=='present'){
        for($a=0;$a<$rows;$a++){
            $result_1->data_seek($a);
            $row = $result_1->fetch_array(MYSQLI_NUM);
            
            $query="UPDATE $s$date[0] SET d$date[2]='A' WHERE Form_ID='$row[0]'";   
            $result = $conn->query($query); if (!$result) die ("Database access failed: " . $conn->error);
        }
        foreach($rollno as $rn){
            if($rn<10) $rn='0'.$rn;            
            $rn=substr($division,0,1).substr($class,0,2).substr($division,1,strlen($division)-1).$rn;
            $query="SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Roll_Number='$rn'";
            $result= $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
            $result->data_seek(0);
            $row = $result->fetch_array(MYSQLI_NUM);

            $query="UPDATE $s$date[0] SET d$date[2]='p' WHERE Form_ID='$row[0]'";            
            $result = $conn->query($query); if (!$result) die ("Database access failed: " . $conn->error);
        }
    }else if($status=='absent'){
        for($a=0;$a<$rows;$a++){
            $result_1->data_seek($a);
            $row = $result_1->fetch_array(MYSQLI_NUM);
            
            $query="UPDATE $s$date[0] SET d$date[2]='p' WHERE Form_ID='$row[0]'";   
            $result = $conn->query($query); if (!$result) die ("Database access failed: " . $conn->error);
        }
        foreach($rollno as $rn){
            if($rn<10) $rn='0'.$rn;            
            $rn=substr($division,0,1).substr($class,0,2).substr($division,1,strlen($division)-1).$rn;
            $query="SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Roll_Number='$rn'";
            $result= $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
            $result->data_seek(0);
            $row = $result->fetch_array(MYSQLI_NUM);

            $query="UPDATE $s$date[0] SET d$date[2]='A' WHERE Form_ID='$row[0]'";            
            $result = $conn->query($query); if (!$result) die ("Database access failed: " . $conn->error);
        }
    }
    echo "<br>Attendence ADDED successfully!!!";
}
?>
</body></html>