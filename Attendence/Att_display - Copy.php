<html><head><title>Student Attendece</title><style>
table, th, td {text-align:left;    border:1px solid gray;    border-collapse: collapse;   font-size:12px; /*height:15px;*/        }
th{    text-align:center;    width:20px;
}
table th:nth-child(2){width:25%;}
</style></head><body><?php require_once 'menubar.php'; require_once 'Sanitize.php';?><center><form method='post' action='Att_display.php' name='form1'><table border=1>
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
require_once 'dbvariables.php';
if(isset($_POST['class']) &&isset($_POST['division']) && isset($_POST['year']) && isset($_POST['month'])){
    $class=Sanitize::sanitizeMySQL($conn, 'class');
    $division=Sanitize::sanitizeMySQL($conn, 'division');
    $year=Sanitize::sanitizeMySQL($conn, 'year');
    $month=Sanitize::sanitizeMySQL($conn, 'month');

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
?>
</body></html>