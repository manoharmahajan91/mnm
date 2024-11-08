<html><head><title>Student Attendece</title><style>
table, th, td {text-align:left;    border:1px solid gray;    border-collapse: collapse;   font-size:15px; /*height:15px;*/        }
th,table#id1 td{    text-align:center;    width:20px;}
</style></head><body><?php require_once 'menubar.php'; require_once 'Sanitize.php';?><center><form method='post' action='Att_daily.php' name='form1'><table border=1>
<tr><td>Class:</td><td><select name='class'>
<option value='11TH'>11TH</option>
<option value='12TH'>12TH</option>
</select></td></tr>
<tr><td>Division:</td><td><select name='division'><?php
for($i=1;$i<11;$i++)
echo "<option value='S$i' selected>S$i</option>";
?></select></td></tr>
<tr><td>Year:</td><td><input type='text' name='year' value='2018'></td></tr>
<tr><td>Month:</td><td>
<select name='month'><option value='jun'>June</option><option value='jul'>July</option><option value='aug'>August</option><option value='sep'>September</option>
<option value='oct'>October</option><option value='nov'>November</option><option value='dec'>December</option><option value='jan' selected>January</option><option value='feb'>February</option>
<option value='mar'>March</option><option value='apr'>April</option><option value='may'>May</option>
</select></td></tr><tr><td colspan=2><center><input type='submit' value='Display Attendence'></center></td></tr></table></form></center>
<?php
require_once 'dbvariables.php';
if(isset($_POST['class']) &&isset($_POST['division']) && isset($_POST['year']) && isset($_POST['month'])){
    $class=Sanitize::sanitizeMySQL($conn, 'class');
    $division=Sanitize::sanitizeMySQL($conn, 'division');
    $year=Sanitize::sanitizeMySQL($conn, 'year');
    $month=Sanitize::sanitizeMySQL($conn, 'month');
    
    $TGA=0;$TGP=0;$TBA=0;$TBP=0;
    echo "<center><h3> $class - $division = $month-$year</h3><table id='id1' cellpadding=8>
    <tr><th rowspan=2>Date</th><th colspan=3>In Catelog</th><th colspan=3>Absent</th><th colspan=3>Present</th></tr>    
    <tr><th>Boys</th><th>Girls</th><th>Total</th><th>Boys</th><th>Girls</th><th>Total</th><th>Boys</th><th>Girls</th><th>Total</th></tr>";
    $query="SELECT COUNT(Form_ID) FROM $month$year WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Initial='MAST')";
    $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
    $rowTB = $result->fetch_array(MYSQLI_NUM);
    $query="SELECT COUNT(Form_ID) FROM $month$year WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Initial='MISS')";
    $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
    $rowTG= $result->fetch_array(MYSQLI_NUM);
    for($i=1;$i<32;$i++){
        $query="SELECT d$i,Form_ID FROM $month$year WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division') AND (d$i='A' OR d$i='P')";
        $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
        $rowD= $result->fetch_array(MYSQLI_NUM);
        //echo "$rowD[0]";
        if($rowD[0]=='P' || $rowD[0]=='A'){
            echo "<tr><td>$i</td><td>$rowTB[0]</td><td>$rowTG[0]</td><td>".($rowTB[0]+$rowTG[0])."</td>";        
            $query="SELECT COUNT(Form_ID) FROM $month$year WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Initial='MAST') AND d$i='A'";
            $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
            $rowAB= $result->fetch_array(MYSQLI_NUM);
            echo "<td>$rowAB[0]</td>";
            $query="SELECT COUNT(Form_ID) FROM $month$year WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Initial='MISS') AND d$i='A'";
            $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
            $rowAG= $result->fetch_array(MYSQLI_NUM);
            echo "<td>$rowAG[0]</td>";
            echo "<td>".($rowAB[0]+$rowAG[0])."</td>";
            
            $query="SELECT COUNT(Form_ID) FROM $month$year WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Initial='MAST') AND d$i='P'";
            $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
            $rowPB= $result->fetch_array(MYSQLI_NUM);
            echo "<td>$rowPB[0]</td>";
            $query="SELECT COUNT(Form_ID) FROM $month$year WHERE Form_ID IN (SELECT Form_ID FROM studinfotable WHERE StudClass='$class' AND StudDiv='$division' AND Initial='MISS') AND d$i='P'";
            $result = $conn->query($query);    if (!$result) die ("Database access failed: " . $conn->error);
            $rowPG= $result->fetch_array(MYSQLI_NUM);
            echo "<td>$rowPG[0]</td>";
            echo "<td>".($rowPB[0]+$rowPG[0])."</td>";            
            echo "</tr>";
        }else{
            echo "<tr><td>$i</td></tr>";
            }
    }
    echo "</table></center>";
}
?>

</body></html>