<?php
require_once 'menubar.php';
require_once 'Sanitize.php'; 
require_once "dbvariables.php";

$query="SELECT Form_ID FROM studinfotable";
$result = $conn->query($query);
if (!$result) die ("Database access failed(Select Month): " . $conn->error);
$rows = $result->num_rows;

if(isset($_POST['month']) && isset($_POST['year'])){
    $month=Sanitize::sanitizeMySQL($conn, 'month');        
    $year=Sanitize::sanitizeMySQL($conn, 'year');
    
    $query = "CREATE TABLE $month$year(
    Form_ID VARCHAR(128) NOT NULL UNIQUE,";
    for($a=1;$a<31;$a++)  $query=$query."d$a VARCHAR(1),";
    $query=$query."d$a VARCHAR(1))";
        
    $result_1 = $conn->query($query); 
    if (!$result_1) die ("Database access failed(Create Month): " . $conn->error);
            
    for($b=0;$b<$rows;$b++){
        $result->data_seek($b);
        $row = $result->fetch_array(MYSQLI_NUM);
            
        $query = "INSERT INTO $month$year VALUES('$row[0]',";
        for($c=1;$c<31;$c++) $query=$query."null,";
        $query=$query."null)";                    
        $result_2 = $conn->query($query); if (!$result_2) die ("Database access failed(Insert): " . $conn->error);
    }   
    echo "<br>Months created successfully!!!";
}
if(isset($_POST['month2']) && isset($_POST['year2'])){
    $month2=Sanitize::sanitizeMySQL($conn, 'month2');        
    $year2=Sanitize::sanitizeMySQL($conn, 'year2');
    
    $query="SELECT Form_ID FROM studinfotable WHERE Form_ID NOT IN (SELECT Form_ID FROM $month2$year2)";
    $result_5 = $conn->query($query);    if (!$result_5) die ("Database access failed(Form Id): " . $conn->error);
    $rows_5 = $result_5->num_rows;

    for($b=0;$b<$rows_5;$b++){
        $result_5->data_seek($b);
        $row_5 = $result_5->fetch_array(MYSQLI_NUM);        
                
        $query = "INSERT INTO $month2$year2 VALUES('$row_5[0]',";
        for($c=1;$c<31;$c++) $query=$query."null,";
        $query=$query."null)";                    
        $result_4 = $conn->query($query); if (!$result_4) die ("Database access failed(Additional Insert): " . $conn->error);
    }   
    echo "<br>Additional Students Added in Month successfully!!!";
}
if(isset($_POST['month1']) && isset($_POST['year1'])){
    $month1=Sanitize::sanitizeMySQL($conn, 'month1');    
    $year1=Sanitize::sanitizeMySQL($conn, 'year1');        
    
    $query = "DROP TABLE $month1$year1";
    $result_3 = $conn->query($query); if (!$result_3) die ("Database access failed(Drop): " . $conn->error);
    
    echo "<br>Months DELETED successfully!!!";
}
?>
<center>
    <h3>ADD Months</h3>
    <form method='post' action='Att.php'>
    <table border=1>
        <tr><td>Month:</td>
        <td><select name='month'>
            <option value='JAN'>January</option>
            <option value='FEB'>February</option>
            <option value='MAR'>March</option>
            <option value='April'>April</option>
            <option value='MAY'>May</option>
            <option value='JUN'>June</option>
            <option value='JUL'>July</option>
            <option value='AUG'>August</option>
            <option value='SEP'>September</option>
            <option value='OCT'>October</option>
            <option value='NOV'>November</option>
            <option value='DEC'>December</option>            
        </select></td>
        <td>Year:</td><td><input type='text' name='year' size=7 value='2018'></td>
        <td><input type='submit' value='Add Month'></td></tr>
    </table>
    </form>
</center>
<br>
<center>
    <h3>Delete Months</h3>
    <form method='post' action='Att.php'>
    <table border=1>
        <tr><td>Month:</td>
        <td><select name='month1'>
            <option value='JAN'>January</option>
            <option value='FEB'>February</option>
            <option value='MAR'>March</option>
            <option value='April'>April</option>
            <option value='MAY'>May</option>
            <option value='JUN'>June</option>
            <option value='JUL'>July</option>
            <option value='AUG'>August</option>
            <option value='SEP'>September</option>
            <option value='OCT'>October</option>
            <option value='NOV'>November</option>
            <option value='DEC'>December</option>            
        </select></td>
        <td>Year:</td><td><input type='text' name='year1' size=7 value='2018'></td>
        <td><input type='submit' value='Delete Month'></td></tr>
    </table>
    </form>
</center>
<br>
<center>
    <h3>Add Additional Students in Months</h3>
    <form method='post' action='Att.php'>
    <table border=1>
        <tr><td>Month:</td>
        <td><select name='month2'>
            <option value='JAN'>January</option>
            <option value='FEB'>February</option>
            <option value='MAR'>March</option>
            <option value='April'>April</option>
            <option value='MAY'>May</option>
            <option value='JUN'>June</option>
            <option value='JUL'>July</option>
            <option value='AUG'>August</option>
            <option value='SEP'>September</option>
            <option value='OCT'>October</option>
            <option value='NOV'>November</option>
            <option value='DEC'>December</option>            
        </select></td>
        <td>Year:</td><td><input type='text' name='year2' size=7 value='2018'></td>
        <td><input type='submit' value='Add Additional Students'></td></tr>
    </table>
    </form>
</center>
<br>