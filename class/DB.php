<?
if (!class_exists("DB")) {

	class DB {
		// 資料連線
		public $conn;

		function __construct() {
			// 建立連線
			try {
                // 連線資料
				$IniSetting = new IniSetting();		
				$DB_user = $IniSetting->get_set('DB_user');
				$DB_pass = $IniSetting->get_set('DB_pass');
				$DB_name = $IniSetting->get_set('DB_name');		
				$DB_SERVER = $IniSetting->get_set('DB_SERVER');
				
				$db_conn = new PDO("sqlsrv:Server=$DB_SERVER;Database=$DB_name", $DB_user, $DB_pass);
				
				$db_conn->exec("set names utf8");
				$this->conn = $db_conn;

			} catch (PDOException $e) {
				$error_msg = '[ServerName:"' . $DB_SERVER . '"] [DB:"' . $DB_name . '"] [User:"' . $DB_user . '"] [Password:"' . $DB_pass . '"] 無法連線到資料庫 錯誤訊息' . $e->getMessage();
				// 寫到log檔
				$error_log_control = new error_log_control();
				$error_log_control->put_Log($error_msg);
				// 資料庫無法連線就直接印出錯誤訊息
				die($error_msg);
				die('DB connect error!');

			}
        }
        /**
		 * 萬用型 下面為範例
		 * @param  string 查詢sql ex select * fromr module where m_id = ?
		 * @param  array[optional] 要查詢的值  $whereArr = array("18");
		 * @return [type]
		 */
		public function query($sql, $dataArr = array()) {
			// 是string 代表只有一個搜尋條件
			if (is_string($dataArr)) {
				$dataArr = array($dataArr);
			}
			try {
				// 放入repare
				$pstmt = $this->conn->prepare($sql);
				// 有錯誤n
				if (!$pstmt->execute($dataArr)) {
					/**
					 * 取得錯誤訊息 會回傳一個array
					 * 0 : SQL的狀態代碼
					 * 1 : 資料庫的 error code
					 * 2 : 資料庫的 error message
					 */
					$error = $pstmt->errorInfo();
					// $debugArr = debug_backtrace();
					// $whereData='[where:'.json_encode($dataArr).']';
					// 建立錯誤訊息
					$errorMsg = '資料庫錯誤訊息:' . $error[2];
					// $errorMsg= '[class:"'.$debugArr[0]['class'].'"] [function:"'.$debugArr[0]['function'].'"] [sql:'.$sql.'] [Data:'.json_encode($dataArr).'] 資料庫錯誤訊息:'.$error[2];
					throw new Exception($errorMsg);
				}
			} catch (Exception $e) {
				$error_msg = '[ServerName:"'.$DB_SERVER.'"] [DB:"'.$DB_name.'"] [User:"'.$DB_user.'"] [Password:"'.$DB_pass.'"] 無法連線到資料庫 錯誤訊息'.$e->getMessage();
				// 資料庫無法連線就直接印出錯誤訊息
				// die($error_msg);
			}
			return $pstmt;
		}

		//新增用
		public function insert($table_name, $dataArr = array()) {
			// 是string 代表只有一個搜尋條件
			if (is_string($dataArr)) {
				$dataArr = array($dataArr);
			}
			$column_str = implode(",", array_keys($dataArr));
			$value_str = "";
			foreach (array_keys($dataArr) as $key => $value) {
			    if ($value_str == "") {
			        $value_str .= ":" . $value;
			    } else {
			        $value_str .= ",:" . $value ;
			    }
			}
			
			$sql = "INSERT INTO $table_name ($column_str) values ($value_str)";
			$lastInsertId="";
			try {
				// 放入repare
				$pstmt = $this->conn->prepare($sql);
				// 有錯誤
				if (!$pstmt->execute($dataArr)) {
					/**
					 * 取得錯誤訊息 會回傳一個array
					 * 0 : SQL的狀態代碼
					 * 1 : 資料庫的 error code
					 * 2 : 資料庫的 error message
					 */
					$error = $pstmt->errorInfo();
					// $debugArr = debug_backtrace();
					// $whereData='[where:'.json_encode($dataArr).']';
					// 建立錯誤訊息
					$errorMsg = '資料庫錯誤訊息:' . $error[2];
					// $errorMsg= '[class:"'.$debugArr[0]['class'].'"] [function:"'.$debugArr[0]['function'].'"] [sql:'.$sql.'] [Data:'.json_encode($dataArr).'] 資料庫錯誤訊息:'.$error[2];
					throw new Exception($errorMsg);
					

				}else{
					$lastInsertId = $this->conn->lastInsertId();

				}
			} catch (Exception $e) {
				$error_msg = '[ServerName:"'.$DB_SERVER.'"] [DB:"'.$DB_name.'"] [User:"'.$DB_user.'"] [Password:"'.$DB_pass.'"] 無法連線到資料庫 錯誤訊息'.$e->getMessage();
				// 資料庫無法連線就直接印出錯誤訊息
				// die($error_msg);
			}
			return $lastInsertId;
		}
		//讀取型
		public function read($sql, $dataArr = array()) {
			// 是string 代表只有一個搜尋條件
			if (is_string($dataArr)) {
				$dataArr = array($dataArr);
			}
			try {
				// 放入repare
				$pstmt = $this->conn->prepare($sql);
				// 有錯誤
				if (!$pstmt->execute($dataArr)) {
					/**
					 * 取得錯誤訊息 會回傳一個array
					 * 0 : SQL的狀態代碼
					 * 1 : 資料庫的 error code
					 * 2 : 資料庫的 error message
					 */
					$error = $pstmt->errorInfo();
					// $debugArr = debug_backtrace();
					// $whereData='[where:'.json_encode($dataArr).']';
					// 建立錯誤訊息
					$errorMsg = '資料庫錯誤訊息:' . $error[2];
					// $errorMsg= '[class:"'.$debugArr[0]['class'].'"] [function:"'.$debugArr[0]['function'].'"] [sql:'.$sql.'] [Data:'.json_encode($dataArr).'] 資料庫錯誤訊息:'.$error[2];
					throw new Exception($errorMsg);
				}
			} catch (Exception $e) {
				$error_msg = '[ServerName:"'.$DB_SERVER.'"] [DB:"'.$DB_name.'"] [User:"'.$DB_user.'"] [Password:"'.$DB_pass.'"] 無法連線到資料庫 錯誤訊息'.$e->getMessage();
				// 資料庫無法連線就直接印出錯誤訊息
				// die($error_msg);
			}
			$fetchall = $pstmt->fetchall();
			foreach ($fetchall as $key => $value) {
			    $now_column = array();
			    foreach ($value as $key2 => $value2) {
			        $now_column[$key2] = htmlentities($value2);
			    }
			    $new_data[] = $now_column;
			}

			return $new_data;
		}
		//修改用
		public function update($table_name, $dataArr = array(),$whereArr = array()) {
			// 是string 代表只有一個搜尋條件
			if (is_string($dataArr)) {
				$dataArr = array($dataArr);
			}
			if (is_string($whereArr)) {
    			$whereArr = array($whereArr);
			}

			$value_str = "";
			foreach ($dataArr as $key => $value) {
    			if ($value_str == "") {
        			$value_str .= $key . "=:" . $key;
    			} else {
        			$value_str .= "," . $key . " = :" . $key;
    			}
			}
			$where_str = "";
			foreach ($whereArr as $key => $value) {
    			if ($where_str == "") {
        			$where_str .= $key . "=:" . $key;
    			} else {
        			$value_str .= " and " . $key . " = :" . $key;
				
    			}
			}
			$dataArr = array_merge($dataArr, $whereArr);

			$sql = "update $table_name set $value_str  where $where_str";

			
			try {
				// 放入repare
				$pstmt = $this->conn->prepare($sql);
				// 有錯誤
				if (!$pstmt->execute($dataArr)) {
					/**
					 * 取得錯誤訊息 會回傳一個array
					 * 0 : SQL的狀態代碼
					 * 1 : 資料庫的 error code
					 * 2 : 資料庫的 error message
					 */
					$error = $pstmt->errorInfo();
					// $debugArr = debug_backtrace();
					// $whereData='[where:'.json_encode($dataArr).']';
					// 建立錯誤訊息
					$errorMsg = '資料庫錯誤訊息:' . $error[2];
					// $errorMsg= '[class:"'.$debugArr[0]['class'].'"] [function:"'.$debugArr[0]['function'].'"] [sql:'.$sql.'] [Data:'.json_encode($dataArr).'] 資料庫錯誤訊息:'.$error[2];
					throw new Exception($errorMsg);
					

				}
			} catch (Exception $e) {
				// $error_msg = '資料庫連線異常! code:Q MS';
				$error_msg = '[ServerName:"'.$DB_SERVER.'"] [DB:"'.$DB_name.'"] [User:"'.$DB_user.'"] [Password:"'.$DB_pass.'"] 無法連線到資料庫 錯誤訊息'.$e->getMessage();
				// 寫到log檔
				$error_log_control = new error_log_control();
				$error_log_control->put_Log($error_msg);
				// 資料庫無法連線就直接印出錯誤訊息
				// die($error_msg);
			}
			return $pstmt;
		}
		//刪除用
		public function delete($table_name, $whereArr = array()) {
			// 是string 代表只有一個搜尋條件
			
			if (is_string($whereArr)) {
    			$whereArr = array($whereArr);
			}

			$where_str = "";
			foreach ($whereArr as $key => $value) {
    			if ($where_str == "") {
        			$where_str .= $key . "=:" . $key;
    			} else {
        			$value_str .= " and " . $key . " = :" . $key;
				
    			}
			}
			$dataArr = $whereArr;

			$sql = "delete from  $table_name where $where_str";

			
			try {
				// 放入repare
				$pstmt = $this->conn->prepare($sql);
				// 有錯誤
				if (!$pstmt->execute($dataArr)) {
					/**
					 * 取得錯誤訊息 會回傳一個array
					 * 0 : SQL的狀態代碼
					 * 1 : 資料庫的 error code
					 * 2 : 資料庫的 error message
					 */
					$error = $pstmt->errorInfo();
					// $debugArr = debug_backtrace();
					// $whereData='[where:'.json_encode($dataArr).']';
					// 建立錯誤訊息
					$errorMsg = '資料庫錯誤訊息:' . $error[2];
					// $errorMsg= '[class:"'.$debugArr[0]['class'].'"] [function:"'.$debugArr[0]['function'].'"] [sql:'.$sql.'] [Data:'.json_encode($dataArr).'] 資料庫錯誤訊息:'.$error[2];
					throw new Exception($errorMsg);
					

				}
			} catch (Exception $e) {
				// $error_msg = '資料庫連線異常! code:Q MS';
				$error_msg = '[ServerName:"'.$DB_SERVER.'"] [DB:"'.$DB_name.'"] [User:"'.$DB_user.'"] [Password:"'.$DB_pass.'"] 無法連線到資料庫 錯誤訊息'.$e->getMessage();
				// 寫到log檔
				$error_log_control = new error_log_control();
				$error_log_control->put_Log($error_msg);
				// 資料庫無法連線就直接印出錯誤訊息
				// die($error_msg);
			}
			return $pstmt;
		}
		/**
		 * 取得數量
		 * @param  [type] $sql     [description]
		 * @param  array  $dataArr [description]
		 * @return [type]          [description]
		 */
		public function count($sql, $dataArr = array()) {
			// 是string 代表只有一個搜尋條件
			if (is_string($dataArr)) {
				$dataArr = array($dataArr);
			}
			$num = 0;
			$pstmt = $this->conn->query($sql, $dataArr);
			if ($row = $pstmt->fetch()) {
				$num = $row["0"];
			}
			return $num;
		}
    }
}
?>