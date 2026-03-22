<?php
class radiumsahil {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    
    public function get_token(){
        return $_SESSION['token'];
    }    
public function check_token($token){
//  $token = $this->get_token();                                
$sql=mysqli_query($this->conn,"SELECT * FROM `login_token` WHERE token='$token' and status='1'");
if(mysqli_num_rows($sql) == 0){
return false;
}else{
$data=mysqli_fetch_assoc($sql);
$user_id=$data['user_id'];
$sql20=mysqli_query($this->conn,"SELECT * FROM `user_wallet` WHERE user_id='$user_id'");
$data1=mysqli_fetch_assoc($sql20);
// Ensure user has a wallet row (e.g. missing from old signups or Google login without wallet)
if ($data1 === null) {
	$zero = 0;
	$stmt = $this->conn->prepare("INSERT INTO user_wallet (user_id, balance, total_recharge, total_otp) VALUES (?, ?, ?, ?)");
	if ($stmt) {
		$stmt->bind_param("iiii", $user_id, $zero, $zero, $zero);
		$stmt->execute();
		$stmt->close();
	}
	$sql20 = mysqli_query($this->conn, "SELECT * FROM `user_wallet` WHERE user_id='$user_id'");
	$data1 = mysqli_fetch_assoc($sql20);
}
if ($data1 !== null) {
	$xy=$this->check_activities($data1['balance'], $data1['total_otp'], $data1['total_recharge'], $user_id);
}
$sql2=mysqli_query($this->conn,"SELECT * FROM `user_data` WHERE id='$user_id' and status='1'");
if(mysqli_num_rows($sql2) == 0){
return false;
}else{
return $user_id;
}
}
}
    public function balancedata() {
                $token = $this->get_token();                
    $user_id=$this->check_token($token);
    if($user_id === false){
    return false;
    }else{
        $sql="SELECT * FROM `user_wallet` WHERE user_id='".$user_id."'";
    	$result=mysqli_query($this->conn, $sql);
        return $result->fetch_array();        
    }    
    }
  public function userdata(){
      $token = $this->get_token();                
    $user_id=$this->check_token($token);
    if($user_id === false){
    return false;
    }else{
        $sql="SELECT * FROM `user_data` WHERE id='".$user_id."'";
    	$result=mysqli_query($this->conn, $sql);
        return $result->fetch_array();                    
}    
  }
    public function userwallet(){
      $token = $this->get_token();                
    $user_id=$this->check_token($token);
    if($user_id === false){
    return false;
    }else{
        $sql="SELECT * FROM `user_wallet` WHERE user_id='".$user_id."'";
    	$result=mysqli_query($this->conn, $sql);
        return $result->fetch_array();                    
}    
  }
   public function userbankaccount()
{
    $token = $this->get_token();
    $user_id = $this->check_token($token);

    if ($user_id === false) {
        return false;
    } elseif ($user_id == "otp") {
        return "otp";
    } else {

        $stmt = $this->conn->prepare("
            SELECT b.*, g.name AS gateway_name 
            FROM bank_accounts b
            LEFT JOIN payment_gateways g ON b.gateway_id = g.id
            WHERE b.user_id = ?
        ");

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $accounts = [];

        while ($row = $result->fetch_assoc()) {
            $accounts[] = $row;
        }

        return !empty($accounts) ? $accounts : null;
    }
}
 public function all_server(){
    $final = array(); // Initialize an empty array to store results

    $sql = mysqli_query($this->conn, "SELECT * FROM `otp_server` WHERE status='1'");
    
    while ($row = $sql->fetch_array()) {
        array_push($final, array(
            'id' => $row['id'],
            'server_name' => $row['server_name'],
        ));
    }
    
    return $final;
}
 public function all_usa_server(){
    $final = array(); // Initialize an empty array to store results

    $sql = mysqli_query($this->conn, "SELECT * FROM `otp_server` WHERE id IN (186,187)");
    
    while ($row = $sql->fetch_array()) {
        array_push($final, array(
            'id' => $row['id'],
            'server_name' => $row['server_name'],
        ));
    }
    
    return $final;
}
public function all_usa_only_server(){
    $final = array(); // Initialize an empty array to store results

    $sql = mysqli_query($this->conn, "SELECT * FROM `otp_server` WHERE api_id = 22");
    
    while ($row = $sql->fetch_array()) {
        array_push($final, array(
            'id' => $row['id'],
            'server_name' => $row['server_name'],
        ));
    }
    
    return $final;
}
 public function all_international_server($api_id){
    $final = array(); // Initialize an empty array to store results

    $sql = mysqli_query($this->conn, "SELECT * FROM `otp_server` WHERE api_id = {$api_id}");
    
    while ($row = $sql->fetch_array()) {
        array_push($final, array(
            'id' => $row['id'],
            'server_name' => $row['server_name'],
        ));
    }
    
    return $final;
}
public function all_logs_categories()
    {
        $final = array(); // Initialize an empty array to store results

        $sql = mysqli_query($this->conn, "SELECT * FROM `categories` WHERE status='1' ORDER BY stt DESC");

        while ($row = $sql->fetch_array()) {
            array_push($final, array(
                'id' => $row['id'],
                'name' => $row['name'],
            ));
        }

        return $final;
    }
    public function all_logs_products($category_id)
    {
        $final = array(); // Initialize an empty array to store results

        $sql = mysqli_query($this->conn, "SELECT * FROM `products` WHERE category_id = '$category_id' AND status='1' ORDER BY stt DESC");
        
        while ($row = $sql->fetch_array()) {
            if($row['api_id'] == 0){
                $total_accounts_query = mysqli_query($this->conn, "SELECT COUNT(*) as total_active_accounts FROM product_details WHERE is_sold = 0 AND product_id='" . $row['id'] . "'");
                $total_active_accounts = mysqli_fetch_assoc($total_accounts_query)['total_active_accounts'];
            }else{
                $total_active_accounts = $row['api_stock'];
            }
            array_push($final, array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'in_stock' => $total_active_accounts,
                'price' => $row['price'],
            ));
        }

        return $final;
    }
    public function log_detail($id)
    {
        $sql = mysqli_query($this->conn, "SELECT * FROM `products` WHERE status='1' AND id = '$id'");

        $product_details = mysqli_fetch_assoc($sql);
        
        if($product_details['api_id'] == 0){
            $total_accounts_query = mysqli_query($this->conn, "SELECT COUNT(*) as total_active_accounts FROM product_details WHERE is_sold = 0 AND product_id='" . $id . "'");
            $total_active_accounts = mysqli_fetch_assoc($total_accounts_query)['total_active_accounts'];
        }else{
            $total_active_accounts = $product_details['api_stock'];
        }
        
        
        $final = array(
            'id' => $product_details['id'],
            'name' => $product_details['name'],
            'description' => $product_details['description'],
            'in_stock' => $total_active_accounts,
            'price' => $product_details['price'],
        );

        return $final;
    }
    public function product_ordered_detail($id, $user_id)
    {
        $sql = mysqli_query(
            $this->conn,
            "SELECT * FROM `order_items` INNER JOIN orders ON order_items.order_id = orders.id
            INNER JOIN products ON order_items.product_id = products.id WHERE order_id = $id AND orders.user_id = $user_id LIMIT 1;"
        );

        if((mysqli_num_rows($sql) == 0)){
            return false;
        }

        $product_details = mysqli_fetch_assoc($sql);
        
        $final = array(
            'id' => $product_details['id'],
            'name' => $product_details['name'],
            'description' => $product_details['description'],
            'price' => $product_details['price'],
        );

        return $final;
    }
   public function number_history(){
        $token = $this->get_token();                
    $user_id=$this->check_token($token);
    if($user_id === false){
    return false;
    }else{
        $final = array(); // Initialize an empty array to store results

    $sql = mysqli_query($this->conn, "SELECT * FROM `active_number` WHERE user_id='".$user_id."' and status='1' ORDER BY id DESC");
    
    while ($row = $sql->fetch_array()) {
        array_push($final, array(
            'number' => $row['number'],
            'service_name' => $row['service_name'],
            'server_id' => $row['server_id'],
            'buy_time' => $row['buy_time'],  
            'sms' => $row['sms_text'],    
            'service_price' => $row['service_price'],                                      
        ));
    }
    
    }
    return $final;
   }
 
      public function transaction_history(){
        $token = $this->get_token();                
    $user_id=$this->check_token($token);
    if($user_id === false){
    return false;
    }else{
        $final = array(); // Initialize an empty array to store results

    $sql = mysqli_query($this->conn, "SELECT * FROM `user_transaction` WHERE user_id='".$user_id."' ORDER BY id DESC");
    
    while ($row = $sql->fetch_array()) {
        array_push($final, array(
            'amount' => $row['amount'],
            'date' => $row['date'],
            'type' => $row['type'],
            'txn_id' => $row['txn_id'],  
            'status' => $row['status'],    
        ));
    }
    
    }
    return $final;
   }
    public function closeConnection() {
        $this->conn->close();
    }
     public function negativebal($balance) {
    if ($balance < 0) {
        return "1";
    } else {
        return "2";
    }
}
public function check_activities($balance, $total_otp, $lifetime, $token) {
    $oauthid = $token;
    
    if ($this->negativebal($balance)==1 || $this->negativebal($total_otp)==1 || $this->negativebal($lifetime)==1 || ($balance > $lifetime)) {
        $sql2 = mysqli_query($this->conn, "SELECT * FROM user_data WHERE id = '$oauthid' AND status = '2'");
        
        if (mysqli_num_rows($sql2) > 0) {
            return "Already Action";
        } else {
            $sql3 = mysqli_query($this->conn, "UPDATE user_data SET status = '2' WHERE id = '$oauthid'");
            return "#1";
        }
    }
    
    // Return a default message if none of the conditions are met
    return "No action required";
}
public function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $random_string = '';

    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $random_string;
}
public function generateRandomString32($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $random_string = '';

    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $random_string;
}
       public function refer_data(){
           $token = $this->get_token();                
    $user_id=$this->check_token($token);
    if($user_id === false){
    return false;
    }else{       
    $random=$this->generateRandomString();
        $sql="SELECT * FROM `refer_data` WHERE user_id='".$user_id."'";
         	$result=mysqli_query($this->conn, $sql);
      if(mysqli_num_rows($result)==0){
          mysqli_query($this->conn,"INSERT INTO refer_data (user_id, balance, own_code, refer_by, transfer, total_earn) VALUES ('".$user_id."', '0', '".$random."', '', '0','0')");
               $sql="SELECT * FROM `refer_data` WHERE user_id='".$user_id."'";
         	$result=mysqli_query($this->conn, $sql);
           return $result->fetch_array();
      }else{        	
        return $result->fetch_array();
        }
    }
    }
    public function refer_users(){
           $token = $this->get_token();                
    $user_id=$this->check_token($token);
    if($user_id === false){
    return false;
    }else{       
    $refers = $this->refer_data();
        $sql="SELECT * FROM `refer_data` WHERE refer_by='".$refers['own_code']."'";
         	$result=mysqli_query($this->conn, $sql);
      if(mysqli_num_rows($result)==0){
      return 0;
      }else{        	
        return mysqli_num_rows($result);
        }
    }
    }
        public function recent_history(){
        $token = $this->get_token();                
    $user_id=$this->check_token($token);
    if($user_id === false){
    return false;
    }else{
        $final = array(); // merged timeline

        // 1) Wallet transactions (credits, etc.)
        $sql = mysqli_query($this->conn, "SELECT * FROM `user_transaction` WHERE user_id='".$user_id."' ORDER BY id DESC LIMIT 15 ");
        while ($sql && ($row = $sql->fetch_array())) {
            $final[] = array(
                'amount' => $row['amount'],
                'date' => $row['date'],
                'type' => $row['type'],
                'txn_id' => $row['txn_id'],
                'status' => $row['status'],
                'direction' => 'credit',
                'source' => 'transaction',
            );
        }

        // 2) Log/product orders (debits)
        // Use created_at when available; fallback to id ordering.
        $orders = mysqli_query($this->conn, "
            SELECT
                o.id AS order_id,
                o.total_amount,
                o.status,
                o.created_at,
                COUNT(oi.id) AS num_items,
                MAX(p.name) AS product_name
            FROM orders o
            INNER JOIN order_items oi ON o.id = oi.order_id
            INNER JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = '".$user_id."'
            GROUP BY o.id
            ORDER BY o.id DESC
            LIMIT 15
        ");

        while ($orders && ($o = $orders->fetch_array())) {
            $pname = (string)($o['product_name'] ?? 'Order');
            $items = (int)($o['num_items'] ?? 0);
            $label = $items > 1 ? ($pname . " ×" . $items) : $pname;
            $final[] = array(
                'amount' => $o['total_amount'],
                'date' => $o['created_at'] ?? '',
                'type' => 'Order: ' . $label,
                'txn_id' => 'order-' . $o['order_id'],
                'status' => $o['status'],
                'direction' => 'debit',
                'source' => 'order',
                'order_id' => $o['order_id'],
            );
        }

        // Sort merged by date desc (fallback to txn_id ordering if date missing)
        usort($final, function($a, $b){
            $ta = strtotime((string)($a['date'] ?? '')) ?: 0;
            $tb = strtotime((string)($b['date'] ?? '')) ?: 0;
            if ($ta === $tb) return 0;
            return ($tb <=> $ta);
        });

        // Keep only latest 15 in merged view
        if (count($final) > 15) {
            $final = array_slice($final, 0, 15);
        }
    }
    return $final;
   }  

    public function recent_activities($limit = 10){
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 10;

        // Large enough pool so we can show true timeline + backfill orders when deposits flood the top N.
        $fetchCap = max(120, $limit * 8);

        $debits = [];
        $credits = [];

        $rowEpoch = function ($row) {
            $ts = (int)($row['event_ts'] ?? 0);
            if ($ts > 0) {
                return $ts;
            }
            $parsed = strtotime((string)($row['date'] ?? ''));
            return ($parsed !== false && $parsed > 0) ? $parsed : 0;
        };

        $sortEvents = function (&$rows) use ($rowEpoch) {
            usort($rows, function ($a, $b) use ($rowEpoch) {
                $ta = $rowEpoch($a);
                $tb = $rowEpoch($b);
                if ($tb !== $ta) {
                    return $tb <=> $ta;
                }
                return (int)($b['_sort_id'] ?? 0) <=> (int)($a['_sort_id'] ?? 0);
            });
        };

        // Airtime debits
        $sql = "
            SELECT
                'debit' AS direction,
                'Airtime' AS type,
                a.api_reference AS txn_id,
                a.amount AS amount,
                CASE WHEN a.status = 1 THEN '1' WHEN a.status = 0 THEN '2' ELSE '3' END AS status,
                a.created_at AS date,
                UNIX_TIMESTAMP(a.created_at) AS event_ts,
                COALESCE(u.name, u.username, u.email) AS user_name,
                CONCAT('Airtime ', COALESCE(n.name,'Network'), ' · ****', RIGHT(COALESCE(a.phone,''), 4)) AS activity_text,
                a.id AS _sort_id
            FROM airtime_orders a
            LEFT JOIN networks n ON n.id = a.network_id
            LEFT JOIN user_data u ON u.id = a.user_id
            ORDER BY a.id DESC
            LIMIT {$fetchCap}
        ";
        $q = mysqli_query($this->conn, $sql);
        while ($q && ($r = $q->fetch_assoc())) { $debits[] = $r; }

        // Data debits (network from plan, fallback to order.network_id)
        $sql = "
            SELECT
                'debit' AS direction,
                'Data' AS type,
                d.api_reference AS txn_id,
                d.amount AS amount,
                CASE WHEN d.status = 1 THEN '1' WHEN d.status = 0 THEN '2' ELSE '3' END AS status,
                d.created_at AS date,
                UNIX_TIMESTAMP(d.created_at) AS event_ts,
                COALESCE(u.name, u.username, u.email) AS user_name,
                CONCAT(
                    'Data ',
                    TRIM(CONCAT(COALESCE(n.name,''), ' ', COALESCE(p.plan_name,''), ' ', COALESCE(p.plan_type,''))),
                    ' · ****',
                    RIGHT(COALESCE(d.phone,''), 4)
                ) AS activity_text,
                d.id AS _sort_id
            FROM data_orders d
            LEFT JOIN data_plans p ON p.id = d.data_plan_id
            LEFT JOIN networks n ON n.id = COALESCE(p.network_id, d.network_id)
            LEFT JOIN user_data u ON u.id = d.user_id
            ORDER BY d.id DESC
            LIMIT {$fetchCap}
        ";
        $q = mysqli_query($this->conn, $sql);
        while ($q && ($r = $q->fetch_assoc())) { $debits[] = $r; }

        // Cable debits
        $sql = "
            SELECT
                'debit' AS direction,
                'Cable' AS type,
                cto.api_reference AS txn_id,
                cto.amount AS amount,
                CASE WHEN cto.status = 1 THEN '1' WHEN cto.status = 0 THEN '2' ELSE '3' END AS status,
                cto.created_at AS date,
                UNIX_TIMESTAMP(cto.created_at) AS event_ts,
                COALESCE(u.name, u.username, u.email) AS user_name,
                CONCAT('Cable ', COALESCE(cp.name,'Provider'), ' · ****', RIGHT(COALESCE(cto.smartcard_number,''), 4)) AS activity_text,
                cto.id AS _sort_id
            FROM cable_tv_orders cto
            LEFT JOIN cable_tv_plans tp ON tp.id = cto.cable_tv_plan_id
            LEFT JOIN cable_tv_providers cp ON cp.id = tp.cable_id
            LEFT JOIN user_data u ON u.id = cto.user_id
            ORDER BY cto.id DESC
            LIMIT {$fetchCap}
        ";
        $q = mysqli_query($this->conn, $sql);
        while ($q && ($r = $q->fetch_assoc())) { $debits[] = $r; }

        // Electricity debits
        $sql = "
            SELECT
                'debit' AS direction,
                'Electricity' AS type,
                eo.api_reference AS txn_id,
                eo.amount AS amount,
                CASE WHEN eo.status = 1 THEN '1' WHEN eo.status = 0 THEN '2' ELSE '3' END AS status,
                eo.created_at AS date,
                UNIX_TIMESTAMP(eo.created_at) AS event_ts,
                COALESCE(u.name, u.username, u.email) AS user_name,
                CONCAT('Electricity ', COALESCE(ep.name,'Provider'), ' · ****', RIGHT(COALESCE(eo.meter_number,''), 4)) AS activity_text,
                eo.id AS _sort_id
            FROM electricity_orders eo
            LEFT JOIN electricity_providers ep ON ep.id = eo.electricity_provider_id
            LEFT JOIN user_data u ON u.id = eo.user_id
            ORDER BY eo.id DESC
            LIMIT {$fetchCap}
        ";
        $q = mysqli_query($this->conn, $sql);
        while ($q && ($r = $q->fetch_assoc())) { $debits[] = $r; }

        // Generic product orders (orders table)
        $sql = "
            SELECT
                'debit' AS direction,
                'Logs' AS type,
                CONCAT('order-', o.id) AS txn_id,
                o.total_amount AS amount,
                CASE WHEN o.status = 1 THEN '1' WHEN o.status = 0 THEN '2' ELSE '3' END AS status,
                MAX(o.created_at) AS date,
                UNIX_TIMESTAMP(MAX(o.created_at)) AS event_ts,
                COALESCE(MAX(u.name), MAX(u.username), MAX(u.email)) AS user_name,
                MAX(p.name) AS activity_text,
                o.id AS _sort_id
            FROM orders o
            INNER JOIN order_items oi ON oi.order_id = o.id
            INNER JOIN products p ON p.id = oi.product_id
            LEFT JOIN user_data u ON u.id = o.user_id
            GROUP BY o.id
            ORDER BY o.id DESC
            LIMIT {$fetchCap}
        ";
        $q = mysqli_query($this->conn, $sql);
        while ($q && ($r = $q->fetch_assoc())) { $debits[] = $r; }

        $sortEvents($debits);

        // Credits: wallet funding only (positive amounts). Avoid treating any future debit rows as "deposits".
        $sql = "
            SELECT
                'credit' AS direction,
                'Deposit' AS type,
                ut.txn_id AS txn_id,
                ut.amount AS amount,
                CASE WHEN ut.status = 1 THEN '1' WHEN ut.status = 0 THEN '2' ELSE '3' END AS status,
                ut.date AS date,
                UNIX_TIMESTAMP(ut.date) AS event_ts,
                COALESCE(u.name, u.username, u.email) AS user_name,
                COALESCE(ut.type, 'Wallet') AS activity_text,
                ut.id AS _sort_id
            FROM user_transaction ut
            LEFT JOIN user_data u ON u.id = ut.user_id
            WHERE (ut.amount + 0) > 0
            ORDER BY ut.id DESC
            LIMIT {$fetchCap}
        ";
        $q = mysqli_query($this->conn, $sql);
        while ($q && ($r = $q->fetch_assoc())) { $credits[] = $r; }

        $sortEvents($credits);

        // Full timeline (newest first), then take $limit — but if deposits dominate the top N,
        // backfill the next chronological debits by swapping out oldest credits in the window.
        $events = array_merge($debits, $credits);
        $sortEvents($events);

        $full = $events;
        $out = array_slice($full, 0, $limit);

        $countDebit = function ($arr) {
            $n = 0;
            foreach ($arr as $row) {
                if (($row['direction'] ?? '') === 'debit') {
                    $n++;
                }
            }
            return $n;
        };

        $totalDebits = $countDebit($full);
        $minDebits = 0;
        if ($totalDebits > 0) {
            $minDebits = min($totalDebits, max(2, (int)ceil($limit * 0.3)));
        }

        if ($minDebits > 0 && $countDebit($out) < $minDebits) {
            $need = $minDebits - $countDebit($out);
            $extra = [];
            for ($i = $limit; $i < count($full) && count($extra) < $need; $i++) {
                if (($full[$i]['direction'] ?? '') === 'debit') {
                    $extra[] = $full[$i];
                }
            }
            $drop = count($extra);
            $out = array_values($out);
            $i = count($out) - 1;
            while ($drop > 0 && $i >= 0) {
                if (($out[$i]['direction'] ?? '') === 'credit') {
                    array_splice($out, $i, 1);
                    $drop--;
                }
                $i--;
            }
            while ($drop > 0 && count($out) > 0) {
                array_pop($out);
                $drop--;
            }
            $out = array_merge($out, $extra);
            $sortEvents($out);
            $out = array_slice($out, 0, $limit);
        }

        foreach ($out as &$ev) {
            unset($ev['_sort_id'], $ev['event_ts']);
        }
        unset($ev);

        return $out;
    }
   public function unread_notifications_count(){
    $token = $this->get_token();                
    $user_id = $this->check_token($token);

    if($user_id === false){
        return 0;
    }

    $sql = mysqli_query(
        $this->conn,
        "SELECT COUNT(*) as total
         FROM user_notifications
         WHERE user_id = '$user_id'
         AND is_read = 0"
    );

    $row = mysqli_fetch_assoc($sql);
    return (int)$row['total'];
    }

       public function api_data(){
           $token = $this->get_token();                
    $user_id=$this->check_token($token);
    if($user_id === false){
    return false;
    }else{     
    $current_time_in_ist = date('Y-m-d H:i:s');        
      
    $random=$this->generateRandomString32();
        $sql="SELECT * FROM `user_api` WHERE user_id='".$user_id."'";
         	$result=mysqli_query($this->conn, $sql);
      if(mysqli_num_rows($result)==0){
          mysqli_query($this->conn,"INSERT INTO user_api (user_id, api_key, create_time) VALUES ('".$user_id."', '".$random."', '".$current_time_in_ist."')");
               $sql="SELECT * FROM `user_api` WHERE user_id='".$user_id."'";
         	$result=mysqli_query($this->conn, $sql);
           return $result->fetch_array();
      }else{        	
        return $result->fetch_array();
        }
    }
    }
}
  
    