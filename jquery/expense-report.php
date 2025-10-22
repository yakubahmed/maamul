<?php 

session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['fdate'])){
    $fdate = mysqli_real_escape_string($con, $_POST['fdate']);
    $tdate = mysqli_real_escape_string($con, $_POST['tdate']);
    $exp_t = mysqli_real_escape_string($con, $_POST['exp_t']);

    if(empty($exp_t) && !empty($fdate) && !empty($tdate)){
        $stmt = "SELECT expense.*, expense_type.name as etype, account.account_name, account.account_number 
        FROM expense, expense_type, account WHERE expense.expense_type = expense_type.expense_type_id 
        AND expense.account = account.account_id AND expense.reg_date >= '$fdate' AND expense.reg_date <= '$tdate'";
        $result = mysqli_query($con, $stmt); 
        $i=0;
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $i++;
                $id = $row['expense_id'];
                $name = $row['expense_name'];
                $type = $row['etype'];
                $desc = $row['description'];
                $amount = $row['amount'];
                $acc = $row['account_name'] . ' - ' . $row['account_number'];
                $date = date("M d, Y", strtotime($row['reg_date']));
               
    
    
                echo "
                    <tr>
                        <td>$i</td>
                        <td>$type</td>
                        <td>$name</td>
                        <td>$desc </td>
                        <td>$date</td>
                        <td> $acc </td>
                        <td> <strong>$ $amount</strong> </td>
    
                    </tr>
                ";
            }

            $stmt = "SELECT SUM(amount) FROM expense WHERE expense.reg_date >= '$fdate' AND expense.reg_date <= '$tdate' ";
            $res = mysqli_query($con, $stmt);
            while($row = mysqli_fetch_array($res)){
                $total = $row[0];
                echo "
                    <tr>
                        <th colspan='6' class='text-right'>Total</th>
                        <th>$total</th>
                    </tr>
                ";
            }

        }else{
            echo "
                <tr>
                    <td colspan='7'> <center> <strong>No record found..</strong> </center> </td>
                </tr>
            ";
        }
    }else{
        $stmt = "SELECT expense.*,expense_type.expense_type_id, expense_type.name as etype, account.account_name, account.account_number 
        FROM expense, expense_type, account WHERE expense.expense_type = expense_type.expense_type_id 
        AND expense.account = account.account_id AND expense.reg_date >= '$fdate' AND expense.reg_date <= '$tdate' AND expense_type.expense_type_id = $exp_t ";
        $result = mysqli_query($con, $stmt); 
        $i=0;
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $i++;
                $id = $row['expense_id'];
                $name = $row['expense_name'];
                $type = $row['etype'];
                $desc = $row['description'];
                $amount = $row['amount'];
                $acc = $row['account_name'] . ' - ' . $row['account_number'];
                $date = date("M d, Y", strtotime($row['reg_date']));
               
    
    
                echo "
                    <tr>
                        <td>$i</td>
                        <td>$type</td>
                        <td>$name</td>
                        <td>$desc </td>
                        <td>$date</td>
                        <td> $acc </td>
                        <td> <strong>$ $amount</strong> </td>
    
                    </tr>
                ";
            }

            $stmt = "SELECT SUM(amount) FROM expense WHERE expense.reg_date >= '$fdate' AND expense.reg_date <= '$tdate'  AND expense.expense_type  =  $exp_t ";
            $res = mysqli_query($con, $stmt);
            while($row = mysqli_fetch_array($res)){
                $total = $row[0];
                echo "
                    <tr>
                        <th colspan='6' class='text-right'>Total</th>
                        <th>$ $total</th>
                    </tr>
                ";
            }

        }else{
            echo "
                <tr>
                    <td colspan='7'> <center> <strong>No record found..</strong> </center> </td>
                </tr>
            ";
        }
    }
}

?>