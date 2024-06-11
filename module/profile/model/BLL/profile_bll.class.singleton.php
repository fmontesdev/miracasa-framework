<?php
	class profile_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = profile_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_loadProfile_user_BLL($token) {
			$token_dec = middleware_auth::decode_token('access', $token);
			$user = $this -> dao -> select_user($this->db, $token_dec['uid'], $token_dec['provider']);
			
			if ($user) {
				return $user;
			} else {
				return "error_user";
			}
		}

		public function get_load_bills_BLL($token) {
			$token_dec = middleware_auth::decode_token('access', $token);
			
			$bills = $this -> dao -> select_bill($this->db, $token_dec['uid']);
			if ($bills) {
				return $bills;
			} else {
				return "no_bills";
			}
		}

		public function get_load_likes_BLL($token) {
			$token_dec = middleware_auth::decode_token('access', $token);

			$likes = $this -> dao -> select_likes($this->db, $token_dec['uid']);
			if ($likes) {
				return $likes;
			} else {
				return "no_likes";
			}
		}

		public function get_delete_like_BLL($args) {
			$token_dec = middleware_auth::decode_token('access', $args[0]);
			
			$delLike = $this -> dao -> delete_like($this->db, $token_dec['uid'], $args[1]);
			if ($delLike) {
				return $args[1];
			} else {
				return "error";
			}
		}

		public function get_update_user_BLL($args) {
			header('Content-Type: application/json'); // Asegúrate de que el contenido es JSON
			// return $args;

			$check = true;

			// Comprobar que el usuario no existe
			$checkUsername = $this -> dao -> select_user_toUpdate($this->db, $args[1]);
			if ($checkUsername) {
				$check = false;
				return "error_user";
			}

			// Comprobar que el email no existe
			$checkEmail = $this -> dao -> select_email_toUpdate($this->db, $args[2]);
			if ($checkEmail) {
				$check = false;
				return "error_email";
			}

			$token_dec = middleware_auth::decode_token('access', $args[0]);

			if ($_FILES && $check) {
				$file = $_FILES['file'];
				$fileName = $file['name'];
				$fileTmpName = $file['tmp_name'];
				$fileSize = $file['size'];
				$fileError = $file['error'];
				$fileType = $file['type'];
			
				// Allow only certain file extensions
				$allowed = array('jpg', 'jpeg', 'png', 'gif');
				$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			
				if (in_array($fileExt, $allowed)) {
					if ($fileError === 0) {
						if ($fileSize <= 1 * 1024 * 1024) { // 1MB
							$fileNameNew = uniqid('', true) . "." . $fileExt;
							$fileDestination = SITE_ROOT . 'uploads/avatar/' . $fileNameNew;
			
							if (move_uploaded_file($fileTmpName, $fileDestination)) {
								// return (["status" => "success", "message" => "File uploaded successfully: $fileNameNew"]);
								$rdo = $this -> dao -> update_user($this->db, $token_dec['uid'], $args[1], $args[2], $args[3], $fileNameNew);
								if($rdo) {
									return "done";
								} else {
									return "error_update";
								}
							} else {
								return "error_upload";
							}
						} else {
							return "error_size";
						}
					} else {
						return "error_upload";
					}
				} else {
					return "error_ext";
				}
			} else {
				return "error_noFile";
			}
		}
	}