<?php
/**
 * Lớp quản lý và xử lý logic dữ liệu liên quan đến người dùng (User)
 */
class UserModel {
	private $db;

	/**
	 * Khởi tạo kết nối cơ sở dữ liệu cho Model
	 * @param mysqli $databaseConnection Kết nối cơ sở dữ liệu
	 */
	public function __construct($databaseConnection) {
		$this->db = $databaseConnection;
	}

	/**
	 * Kiểm tra xem cấu trúc địa chỉ Email có đúng định dạng chuẩn hay không
	 * @param string $email Địa chỉ email cần kiểm tra
	 * @return bool True nếu đúng định dạng, ngược lại False
	 */
	public function isValidEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}

	/**
	 * Kiểm tra xem Email đã tồn tại trong hệ thống chưa
	 * @param string $email Địa chỉ email cần kiểm tra
	 * @return bool True nếu đã tồn tại, ngược lại False
	 */
	public function isUserExists($email) {
		$stmt = $this->db->prepare("SELECT Email FROM user WHERE Email = ?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();
		
		$exists = $stmt->num_rows > 0;
		$stmt->close();
		
		return $exists;
	}
    
    /**
	 * Lấy thông tin tài khoản chi tiết dựa theo địa chỉ Email
	 * @param string $email Địa chỉ email đăng nhập của người dùng
	 * @return array|null Mảng chứa thông tin cơ bản của User hoặc null nếu không tồn tại
	 */
	public function getUserByEmail($email) {
		$stmt = $this->db->prepare("SELECT MaUser, Email, MatKhau, Role, HoTen FROM user WHERE Email = ?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$user = $result->fetch_assoc();
		$stmt->close();
		
		return $user ? $user : null;
	}
    
	/**
	 * Thực hiện đăng ký tài khoản cho Ứng viên (Role = 0)
	 * @param array $userData Mảng chứa thông tin chung của User theo Database
	 * @return bool True nếu đăng ký thành công, ngược lại False
	 */
	public function registerCandidate($userData) {
		$this->db->begin_transaction();

		try {
			// Thứ tự cột chuẩn CSDL: MaUser, Email, MatKhau, Role, HoTen, NgaySinh, GioiTinh, SDT, DiaChi
			$sqlUser = "INSERT INTO user (MaUser, Email, MatKhau, Role, HoTen, NgaySinh, GioiTinh, SDT, DiaChi) 
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmtUser = $this->db->prepare($sqlUser);
			
			$stmtUser->bind_param(
				"sssississ", 
				$userData['maUser'], 
				$userData['email'], 
				$userData['matKhauHashed'], 
				$userData['role'], 
				$userData['hoTen'], 
				$userData['ngaySinh'], 
				$userData['gioiTinh'], 
				$userData['sdt'], 
				$userData['diaChi']
			);
			$stmtUser->execute();
			$stmtUser->close();

			$sqlCandidate = "INSERT INTO ungvien (MaUngVien, TrangThaiTimViec) VALUES (?, 1)";
			$stmtCandidate = $this->db->prepare($sqlCandidate);
			$stmtCandidate->bind_param("s", $userData['maUser']);
			$stmtCandidate->execute();
			$stmtCandidate->close();

			$this->db->commit();
			return true;
		} catch (Exception $e) {
			$this->db->rollback();
			return false;
		}
	}

	/**
	 * Thực hiện đăng ký tài khoản cho Nhà tuyển dụng (Role = 1)
	 * @param array $userData Mảng chứa thông tin chung của User theo Database
	 * @param array $employerData Mảng chứa thông tin doanh nghiệp bổ sung
	 * @return bool True nếu đăng ký thành công, ngược lại False
	 */
	public function registerEmployer($userData, $employerData) {
		$this->db->begin_transaction();

		try {
			$sqlUser = "INSERT INTO user (MaUser, Email, MatKhau, Role, HoTen, NgaySinh, GioiTinh, SDT, DiaChi) 
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmtUser = $this->db->prepare($sqlUser);
			
			$stmtUser->bind_param(
				"sssississ", 
				$userData['maUser'], 
				$userData['email'], 
				$userData['matKhauHashed'], 
				$userData['role'], 
				$userData['hoTen'], 
				$userData['ngaySinh'], 
				$userData['gioiTinh'], 
				$userData['sdt'], 
				$userData['diaChi']
			);
			$stmtUser->execute();
			$stmtUser->close();

			$sqlEmployer = "INSERT INTO nhatuyendung (MaNhaTuyenDung, TenCongTy, Website, LinhVuc, MaSoThue) 
							VALUES (?, ?, ?, ?, ?)";
			$stmtEmployer = $this->db->prepare($sqlEmployer);
			$stmtEmployer->bind_param(
				"ssssi", 
				$userData['maUser'], 
				$employerData['tenCongTy'],
				$employerData['website'],
				$employerData['linhVuc'],
				$employerData['maSoThue']
			);
			$stmtEmployer->execute();
			$stmtEmployer->close();

			$this->db->commit();
			return true;
		} catch (Exception $e) {
			$this->db->rollback();
			return false;
		}
	}
}
?>