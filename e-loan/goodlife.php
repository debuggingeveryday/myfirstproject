<?php
session_start();

class GoodLife
{

    public function __construct()
    {

        return Self::db();
    }

    public static function db()
    {
        $conn = mysqli_connect("localhost", "root", "Iskaramaal1!", "loanapp");

        if (!$conn) {
            echo "not connected";
        } else {
            return $conn;
        }
    }

    public static function verify($employee_id)
    {
        $db = Self::db();

        $query = "SELECT employee_code, fullname, mobileno FROM `creditors` WHERE `employee_code` = '$employee_id'";
        $query = mysqli_query($db, $query);
        $query = mysqli_fetch_array($query, MYSQLI_NUM);

        if ($query) {
            $data = array(
                "emp_id" => $query[0],
                "fullname" => $query[1],
                "mobileno" => $query[2],
                "status" => "success"
            );

            return print(json_encode($data));
        } else {
            $data = array(
                "status" => "error"
            );

            return print(json_encode($data));
        }
    }

    public static function login($employee_id)
    {
        Self::session($employee_id);
    }

    public static function session($employee_id)
    {
        $db = Self::db();
        $randno = rand(1, (int) 999999999999999999999);
        $session_id = md5($randno);

        $_SESSION["session_code"] = $session_id;
        $_SESSION["session_id"] = $employee_id;
        $_SESSION["session_date"] = date("Y/m/d");

        $query = "INSERT INTO `session`(`id`, `session_code`, `session_id`, `session_date`) 
        VALUES (NULL, '" . $session_id . "', '" . $employee_id . "','" . date("Y/m/d") . "')";

        if ($result = mysqli_query($db, $query)) {
            echo "success";
        } else {
            echo "ERROR: Could not able to execute $query. " . mysqli_error($db);
        }
    }

    public static function check_session()
    {
        $db = Self::db();
        $session_code = (isset($_SESSION["session_code"])) ? $_SESSION["session_code"] : "";
        $session_id = (isset($_SESSION["session_id"])) ? $_SESSION["session_id"] : "";
        $session_date = date("Y/m/d");


        $query = "SELECT `session_code`, `session_id`, `session_date` 
        FROM `session` WHERE `session_code` = '" . $session_code . "' &&  `session_id` = '" . $session_id . "' &&  `session_date` = '" . $session_date . "'";
        $query = mysqli_query($db, $query);
        $query = mysqli_fetch_array($query, MYSQLI_NUM);

        return $query;
    }

    public static function clear_session_logout()
    {
        $db = Self::db();

        $query = "TRUNCATE TABLE `session`";
        $query = mysqli_query($db, $query);
        session_destroy();

        header("Location: index.php");

        exit();
    }

    public static function clear_session()
    {
        $db = Self::db();

        $query = "TRUNCATE TABLE `session`";
        $query = mysqli_query($db, $query);
        session_destroy();
    }

    public static function first_comaker($id)
    {
        $db = Self::db();

        $query = "SELECT employee_code, fullname FROM `creditors` WHERE `employee_code` = '$id'";
        $query = mysqli_query($db, $query);
        $query = mysqli_fetch_array($query, MYSQLI_NUM);

        if ($query) {

            $data = array(
                "emp_id" => $query[0],
                "fullname" => $query[1],
                "status" => "success"
            );

            return print(json_encode($data));
        } else {

            $data = array(
                "status" => "id not found"
            );

            return print(json_encode($data));
        }
    }

    public static function second_comaker($coid)
    {
        $db = Self::db();

        $query = "SELECT employee_code, fullname FROM `creditors` WHERE `employee_code` = '$coid'";
        $query = mysqli_query($db, $query);
        $query = mysqli_fetch_array($query, MYSQLI_NUM);

        if ($query) {
            $data = array(
                "emp_id" => $query[0],
                "fullname" => $query[1],
                "status" => "success"
            );

            return print(json_encode($data));
        } else {
            $data = array(
                "status" => "id not found"
            );

            return print(json_encode($data));
        }
    }

    public static function prescribe_info($id)
    {
        $db = Self::db();

        $query = "SELECT `employed_date`, `emp_classification` FROM `creditors` WHERE `employee_code` = '$id'";
        $query = mysqli_query($db, $query);
        $query = mysqli_fetch_array($query, MYSQLI_NUM);

        $employed_start_date = $query[0];
        $emp_classification = $query[1];
        $date_today = date("m/d/Y");

        $employed_start_date = new DateTime($employed_start_date);
        $date_today = new DateTime($date_today);

        $final_empyrs = $date_today->diff($employed_start_date);
        $final_empyrs = $final_empyrs->y;

        $class_desc = "SELECT from_years, to_years, class_desc, amount FROM `class_bracket`";
        $class_desc = mysqli_query($db, $class_desc);


        while ($rows = mysqli_fetch_assoc($class_desc)) {
            if ($rows["class_desc"] === $emp_classification) {
                $from_years = $rows["from_years"];
                $to_years = $rows["to_years"];

                if ($from_years >= $final_empyrs && $final_empyrs <= $to_years) {

                    $data = array(
                        "class_bracket" => $rows["class_desc"],
                        "from_years" => $from_years,
                        "to_years" => $to_years,
                        "amount" => $rows["amount"]
                    );

                    return print_r(json_encode($data));
                } else { }
            }
        }
    }

    public static function update_mobilenum($mobile_number, $id)
    {
        $db = Self::db();
        $query = "UPDATE `creditors` SET `mobileno`= " . $mobile_number . " WHERE employee_code = " . $id;
        $query = mysqli_query($db, $query);

        if (!$query) {
            return print("ERROR: Could not able to execute $query. " . mysqli_error($db));
        } else {
            return print("success");
        }
    }

    public static function check_comaker($a, $b)
    {
        $db = Self::db();
        $query = "SELECT `employed_date` FROM `creditors` WHERE `employee_code` = '$a'";
        $query = mysqli_query($db, $query);
        $query = mysqli_fetch_array($query, MYSQLI_NUM);

        $employed_start_date = $query[0];
        $date_today = date("m/d/Y");

        $employed_start_date = new DateTime($employed_start_date);
        $date_today = new DateTime($date_today);

        $final_empyrs = $date_today->diff($employed_start_date);
        $first_comaker = $final_empyrs->y;

        $query = "SELECT `employed_date` FROM `creditors` WHERE `employee_code` = '$b'";
        $query = mysqli_query($db, $query);
        $query = mysqli_fetch_array($query, MYSQLI_NUM);

        $employed_start_date = $query[0];
        $date_today = date("m/d/Y");

        $employed_start_date = new DateTime($employed_start_date);
        $date_today = new DateTime($date_today);

        $final_empyrs = $date_today->diff($employed_start_date);
        $second_comaker = $final_empyrs->y;

        if ($first_comaker >= 5 && $second_comaker >= 5) {
           return print("success");
        } else if ($first_comaker >= 5) {
             return print("error2");
        }
         else if ($second_comaker >= 5) {
             return print("error1");
        }
        else {
             return print("errorall");
        }
    }


    public static function employee_details($id)
    {
        $db = Self::db();

        $lengthofservice  = "SELECT `employed_date` FROM `creditors` WHERE `employee_code` = '$id'";
        $lengthofservice = mysqli_query($db, $lengthofservice);
        $lengthofservice = mysqli_fetch_array($lengthofservice, MYSQLI_NUM);

        $lengthofservice = $lengthofservice[0];
        $date_today = date("m/d/Y");

        $lengthofservice = new DateTime($lengthofservice);
        $date_today = new DateTime($date_today);

        $lengthofservice = $date_today->diff($lengthofservice);

        $paysched = "SELECT `salary_type` FROM `creditors`";
        $paysched = mysqli_query($db, $paysched);
        $paysched = mysqli_fetch_array($paysched, MYSQLI_NUM);
        $paysched = $paysched[0];

        $data = array(
            "paysched" => $paysched,
            "lengthofservice" => $lengthofservice
        );

        return $data;
    }

    public static function save($data)
    {
        $db = Self::db();

        $id = $data["id"];
        $amount = $data["amount"];
        $terms = $data["terms"];
        $s_amount = $data["s_amount"];
        $purpose = $data["purpose"];
        $fcm_id = $data["fcm_id"];
        $scm_id = $data["scm_id"];
        $fcm_name = $data["fcm_name"];
        $scm_name = $data["scm_name"];

        $loan_principal = $amount;


        $interest = $amount * ($terms * 0.1);
        $total = $interest + $amount;
        $monthly = ($total / $terms);

        $s_interest = $s_amount * ($terms * 0.1);
        $s_total = $s_interest + $s_amount;
        $s_monthly = ($s_total / $terms);

        $lengthofservice = Self::employee_details($id);
        $lengthofservice = $lengthofservice["lengthofservice"];

        $paysched = Self::employee_details($id);
        $paysched = $paysched["paysched"];

        $fcm_los = Self::employee_details($fcm_id);
        $scm_los = Self::employee_details($scm_id);

        $fcm_los = $fcm_los["lengthofservice"]->y . " Year(s) & " . $fcm_los["lengthofservice"]->m . "Month(s)";
        $scm_los = $scm_los["lengthofservice"]->y . " Year(s) & " . $scm_los["lengthofservice"]->m . "Month(s)";
        $los = $lengthofservice->y . " Year(s) & " . $lengthofservice->m . "Month(s)";

        $ave_monthly = ($monthly * $terms) / $terms;

        $todays_date = date("y-m-d h:i:sa");
        $today = strtotime($todays_date);
        date_default_timezone_set('Asia/Manila');
        $created_on = date("Y-m-d H:i:s", $today);

        $query = "INSERT INTO `transloan`(`id`, `creditor_id`, `product_id`, `applied_amount`, `terms`, `purpose`, `loan_principal`, `loan_interest`, `loan_total`, `loan_ammortization`, `suggested_principal`, `suggested_interest`, `suggested_total`, `suggested_ammortization`, `approved_amount`, `approved_term`, `created_by`, `created_on`, `comaker1`, `comaker2`, `status`, `updated_dt`, `updated_by`, `lengthofservice`, `remarks`, `pin`, `paysched`, `validators_remark`, `co1_name`, `co2_name`, `co1_los`, `co2_los`, `los`, `ave_month_pay`) VALUES (NULL, '$id', NULL, '$amount', '$terms', '$purpose', $loan_principal, '$interest', '$total', '$monthly', $s_amount, '$s_interest', '$s_total', '$s_monthly', 0, NULL, NULL, '$created_on', '$fcm_id', '$scm_id', 'PENDING', NULL, 'test', '$lengthofservice->y', 'test', 'test', '$paysched', NULL, '$fcm_name', '$scm_name', '$fcm_los', '$scm_los', '$los', '$ave_monthly')";

        // return print($query);
        if ($query = mysqli_query($db, $query)) {
            echo "success";
        } else {
            echo "ERROR: Could not able to execute $query. " . mysqli_error($db);
        }
    }
}
