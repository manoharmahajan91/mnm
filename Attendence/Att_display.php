<html><head><title>Student Attendece</title><style>
table#id1{width:80%}
table, th, td {text-align:left;    border:1px solid gray;    border-collapse: collapse;   font-size:12px; /*height:15px;*/}
th{    text-align:center;    width:20px;
}
table th:nth-child(2){width:25%;}
</style></head><body><?php require_once 'menubar.php'; require_once 'Sanitize.php';require_once 'dbvariables.php';?><center><form method='post' action='Att_display.php' name='form1'><table border=1  id='id1'>
<tr><td>Class:</td><td><select name='class'>
<option value='11TH'>11TH</option>
<option value='12TH'>12TH</option>
</select></td><td>Division:</td><td><select name='division'><?php
for($i=5;$i<6;$i++)
echo "<option value='S$i' selected>S$i</option>";
?></select></td><td>Year:</td><td><input type='text' name='year' value='2018'></td>
<td>Month:</td><td>
<select name='month'><option value='jun'>June</option><option value='jul'>July</option><option value='aug'>August</option><option value='sep'>September</option>
<option value='oct'>October</option><option value='nov'>November</option><option value='dec'>December</option><option value='jan' selected>January</option><option value='feb'>February</option>
<option value='mar'>March</option><option value='apr'>April</option><option value='may'>May</option>
</select></td><td colspan=2><center><input type='submit' value='Display Attendence'></center></td></tr></table></form></center>
<?php 
if(isset($_POST['class']) && isset($_POST['division']) && isset($_POST['year']) && isset($_POST['month'])){
    $class=Sanitize::sanitizeMySQL($conn, 'class');
    $division=Sanitize::sanitizeMySQL($conn, 'division');
    $year=Sanitize::sanitizeMySQL($conn, 'year');
    $month=Sanitize::sanitizeMySQL($conn, 'month');
    echo "<center><form method='post' action='Att_display.php'><table id='id1'><tr><td>Select Date of $month-$year</td>
    <td>Date:</td><td><input type='date' name='date' value=''></td>
    <td>Status:</td><td><select name='status'><option value='Absent'>Absent</option><option value='Present'>Present</option></select></td>
    <td><input type='submit' value='Add Attendence'></td></tr>
    <input type='hidden' name='class' value='$class'>
    <input type='hidden' name='division' value='$division'>
    <input type='hidden' name='year' value='$year'>
    <input type='hidden' name='month' value='$month'>
    <input type='hidden' name='adt' value='yes'>
    </table></form></center>";
    if(isset($_POST['date']) && isset($_POST['status']) && isset($_POST['adt'])){
        $date=Sanitize::sanitizeMySQL($conn, 'date');
        $status=Sanitize::sanitizeMySQL($conn, 'status');        
        $adt=Sanitize::sanitizeMySQL($conn, 'adt');        
        if($date!=''){
            $date_1=$date;
            $date=explode('-',$date);
            switch($date[1]){
                case '01': $s='jan';break;case '02': $s='feb';break;case '03': $s='mar';break;case '04': $s='apr';break;case '05': $s='may';break;case '06': $s='jun';break;
                case '07': $s='jul';break;case '08': $s='aug';break;case '09': $s='sep';break;case '10': $s='oct';break;case '11': $s='nov';break;case '12': $s='dec';break;
            }
            switch($date[2]){
                case '01':$date[2]='1';break;case '02':$date[2]='2';break;case '03':$date[2]='3';break;case '04':$date[2]='4';break;case '05':$date[2]='5';break;
                case '06':$date[2]='6';break;case '07':$date[2]='7';break;case '08':$date[2]='8';break;case '09':$date[2]='9';break;
            }
            $day="d$date[2]";
            echo "<center><form method='post' action='Att_display.php'>
            <table id='id1'><tr><td>Enter Roll Numbers for date:$date_1:</td><td><input type='text' name='rollno' autofocus></td>
            <td><input type='submit' value='$status'></td></tr>
            <input type='hidden' name='class' value='$class'>
            <input type='hidden' name='division' value='$division'>
            <input type='hidden' name='year' value='$year'>
            <input type='hidden' name='month' value='$month'>
            <input type='hidden' name='date' value='$date_1'>
            <input type='hidden' name='status' value='$status'>
            <input type='hidden' name='adt' value='no'>            
            </table></form></center>";            
            if($date[0]==$year && $s==$month){                
                if($status=='Present') $status_1="Absent";
                else $status_1="Present";
                if($adt=='yes')
                addAtt($conn,$status_1,"$s$date[0]",$day,$class,$division);                
            }else die("Select Proper date");            
        }else die("Select date");
    }
    echo "<center><h3> $class - $division = $month-$year</h3>";    
    
    echo "<table border=1>";
    echo "<tr><th width='20px'>RN</th><th>Name of Students</th>";
    for($b=1;$b<32;$b++){
       $s=sprintf('%02s',$b);
       echo "<th>$s</th>";
    }
    echo "<th>P</th><th>A</th><th>Total WD</th></tr>";
        
    $query="SELECT studinfotable.Roll_Number,studinfotable.Initial,studinfotable.Surname,studinfotable.Student_Name,studinfotable.Father_Name,$month$year.* FROM studinfotable, $month$year WHERE StudClass='$class' AND StudDiv='$division' 
    AND $month$year.Form_ID=studinfotable.Form_ID ORDER BY studinfotable.Roll_Number";
    $result = $conn->query($query);    
    if (!$result) die ("Database access failed: " . $conn->error);
    $rows = $result->num_rows;    
    $TGA=0;$TGP=0;$TBA=0;$TBP=0;
    $a=0;$p=0;$wd=0;
    
    for($k=0;$k<$rows;$k++){
        $result->data_seek($k);
        $row = $result->fetch_array(MYSQLI_NUM);
        $a=0;$p=0;        
        $s=sprintf('%02s',$row[0]);
        echo "<tr><td><center>$s</center></td>";
        echo "<td>".strtoupper("$row[1] $row[2] $row[3] $row[4]")."</td>";        
        for($b=6;$b<37;$b++){
            $c=$row[$b];
            if($c=='A'){
                echo "<td><center><font color=red>$row[$b]</font></center></td>";$a++;
            }else if($c=='P'){
                echo "<td><center><font color=green>$row[$b]</font></center></td>";$p++;
            }else echo "<td><center><font color=green>$row[$b]</font></center></td>";
        }
        if($k==0) $wd=$a+$p;
        echo "<td><center>$p</center></td><td><center>$a</center></td><td><center>".($a+$p)."</center></td></tr>";        
    }
    
    echo "<tr><td colspan=2><center>Absent_Girls</center></td>";
    for($i=1;$i<32;$i++){        
        $query="SELECT COUNT(d$i) FROM $month$year WHERE d$i='A' AND Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Initial='MISS')";        
        $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
        $row = $result->fetch_array(MYSQLI_NUM);
        echo "<td><center>".$row[0]."</center></td>";$TGA+=$row[0];
    }
    echo "<tr><td colspan=2><center>Present_Girls</center></td>";
    for($i=1;$i<32;$i++){
        $query="SELECT COUNT(d$i) FROM $month$year WHERE d$i='P' AND Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Initial='MISS')";
        $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
        $row = $result->fetch_array(MYSQLI_NUM);
        echo "<td><center>".$row[0]."</center></td>";$TGP+=$row[0];
    }
    echo "</tr><tr><td colspan=2><center>Absent_Boys</center></td>";
    for($i=1;$i<32;$i++){
        $query="SELECT COUNT(d$i) FROM $month$year WHERE d$i='A' AND Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Initial='MAST')";
        $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
        $row = $result->fetch_array(MYSQLI_NUM);
        echo "<td><center>".$row[0]."</center></td>";$TBA+=$row[0];
    }
    echo "</tr><tr><td colspan=2><center>Present_Boys</center></td>";
    for($i=1;$i<32;$i++){
        $query="SELECT COUNT(d$i) FROM $month$year WHERE d$i='P' AND Form_ID IN (SELECT Form_ID FROM studinfotable WHERE  StudClass='$class' AND StudDiv='$division' AND  Initial='MAST')";
        $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
        $row = $result->fetch_array(MYSQLI_NUM);
        echo "<td><center>".$row[0]."</center></td>";$TBP+=$row[0];
    }    
    echo "</tr></table><br><table border=1><tr><td>Total_Girls_Absent</td></td><td>Total_Girls_Present</td></td><td>Total_Boys_Absent</td></td><td>Total_Boys_Present</td></tr>";
    echo "<tr><td><center>$TGA</center></td><td><center>$TGP</center></td><td><center>$TBA</center></td><td><center>$TBP</center></td></tr></table></center>";
    if($wd==0) $wd=1;
    echo "<center><table><tr><td><center>Avg_Girls_Present</center></td><td><center>Avg_Boys_Present</center></td><td><center>Avg Total</center></td></tr><tr><td><center>".sprintf('%.2f',$TGP/$wd)."</center></td><td><center>".sprintf('%.2f',$TBP/$wd)."</center></td><td><center>".sprintf('%.2f',($TGP+$TBP)/$wd)."</center></td></tr></table></center>";
}
function addAtt($conn,$status,$month,$day,$class,$division){
    if($status == 'Present') $pa='P'; else  $pa='A';
    $query="UPDATE $month SET $day='$pa' WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division')";
    $result_ = $conn->query($query); if (!$result_) die ("Database access failed: " . $conn->error);        
}
if(isset($_POST['rollno']) && isset($_POST['status']) && isset($_POST['date']) && isset($_POST['class']) && isset($_POST['division']) && isset($_POST['year'])){
    $rollno=Sanitize::sanitizeMySQL($conn, 'rollno');
    $class=Sanitize::sanitizeMySQL($conn, 'class');
    $division=Sanitize::sanitizeMySQL($conn, 'division');
    $year=Sanitize::sanitizeMySQL($conn, 'year');    
    $status=Sanitize::sanitizeMySQL($conn, 'status');
    $date=Sanitize::sanitizeMySQL($conn, 'date');
    $text=$rollno; echo $text;
    if($rollno<10) $rollno='0'.$rollno; 
    $rollno=substr($division,0,1).substr($class,0,2).substr($division,1,strlen($division)).$rollno;
    if($status=="Present") $pa='P';else $pa='A';    //echo "$pa";
    if($date!=''){
        $date=explode('-',$date);
        switch($date[1]){
            case '01': $s='jan';break;case '02': $s='feb';break;case '03': $s='mar';break;case '04': $s='apr';break;case '05': $s='may';break;case '06': $s='jun';break;
            case '07': $s='jul';break;case '08': $s='aug';break;case '09': $s='sep';break;case '10': $s='oct';break;case '11': $s='nov';break;case '12': $s='dec';break;
        }
        switch($date[2]){
            case '01':$date[2]='1';break;case '02':$date[2]='2';break;case '03':$date[2]='3';break;case '04':$date[2]='4';break;case '05':$date[2]='5';break;
            case '06':$date[2]='6';break;case '07':$date[2]='7';break;case '08':$date[2]='8';break;case '09':$date[2]='9';break;
        }
        $day="d$date[2]";   
    }else die("Select date");
    $query="UPDATE $month$year SET $day='$pa' WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Roll_Number='$rollno')";    
    $result = $conn->query($query); if (!$result) die ("Database access failed: " . $conn->error);                    
    if($text!=''){
        if (file_exists("$month$year.txt")){
            $fh = fopen("$month$year.txt", 'r+') or die("E:Failed to open file");   
            $text1 = fgets($fh);
            fseek($fh, 0, SEEK_END);
            fwrite($fh, $text.",") or die("Could not write to file");
            fclose($fh);
        }else{
            $fh = fopen("$month$year.txt", 'w') or die("C:Failed to create file");
            
            fwrite($fh, $text) or die("Could not write to file");
            fclose($fh);
        }
    }
}
?>
</body></html>