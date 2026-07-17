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
	 * Cập nhật mật khẩu mới cho người dùng dựa theo Email
	 * @param string $email Địa chỉ email cần đổi mật khẩu
	 * @param string $newPasswordHashed Mật khẩu mới đã được mã hóa
	 * @return bool True nếu thành công, ngược lại False
	 */
	public function updatePassword($email, $newPasswordHashed) {
		$sql = "UPDATE user SET MatKhau = ? WHERE Email = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("ss", $newPasswordHashed, $email);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}
	
	/**
	 * Lấy thông tin tài khoản chi tiết dựa theo mã định danh User (Không lấy mật khẩu)
	 * 
	 * @param string $maUser Mã định danh của người dùng hiện tại trong hệ thống.
	 * @return array|null Mảng chứa thông tin cá nhân chi tiết hoặc null nếu không tồn tại.
	 */
	public function getUserById($maUser) {
		$sql = "SELECT MaUser, Email, Role, HoTen, NgaySinh, GioiTinh, SDT, DiaChi FROM user WHERE MaUser = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("s", $maUser);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		$stmt->close();
		
		return $user ? $user : null;
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

	/**
	 * Cập nhật thông tin cá nhân của người dùng (Không thay đổi Email, Mật khẩu, Role)
	 * 
	 * @param string $maUser Mã người dùng cần cập nhật
	 * @param array $data Mảng chứa thông tin mới (hoTen, ngaySinh, gioiTinh, sdt, diaChi)
	 * @return bool True nếu cập nhật thành công, ngược lại False
	 */
	public function updateUserProfile($maUser, $data) {
		$sql = "UPDATE user SET HoTen = ?, NgaySinh = ?, GioiTinh = ?, SDT = ?, DiaChi = ? WHERE MaUser = ?";
		$stmt = $this->db->prepare($sql);
		
		// GioiTinh có thể là NULL, cần xử lý bind_param phù hợp
		$gioiTinh = ($data['gioiTinh'] === '') ? null : (int)$data['gioiTinh'];
		
		$stmt->bind_param(
			"ssisss", 
			$data['hoTen'], 
			$data['ngaySinh'], 
			$gioiTinh, 
			$data['sdt'], 
			$data['diaChi'], 
			$maUser
		);
		
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	/**
	 * Lấy thông tin chi tiết đầy đủ của Công ty/Nhà tuyển dụng (kết hợp bảng user và nhatuyendung)
	 * @param string $maUser
	 * @return array|null
	 */
	public function getEmployerById($maUser) {
		$sql = "SELECT u.MaUser, u.Email, u.Role, u.SDT, u.DiaChi, n.TenCongTy, n.Website, n.LinhVuc, n.MaSoThue 
				FROM user u 
				LEFT JOIN nhatuyendung n ON u.MaUser = n.MaNhaTuyenDung 
				WHERE u.MaUser = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("s", $maUser);
		$stmt->execute();
		$result = $stmt->get_result();
		$employer = $result->fetch_assoc();
		$stmt->close();
		return $employer;
	}

	/**
	 * Cập nhật thông tin dành riêng cho tài khoản Công ty
	 * @param string $maUser
	 * @param array $data Mảng chứa sdt, diaChi (bảng user) và website, linhVuc (bảng nhatuyendung)
	 * @return bool
	 */
	public function updateEmployerProfile($maUser, $data) {
		$this->db->begin_transaction();
		try {
			// 1. Cập nhật bảng user (chỉ sửa Số điện thoại và Địa chỉ)
			$sqlUser = "UPDATE user SET SDT = ?, DiaChi = ? WHERE MaUser = ?";
			$stmtUser = $this->db->prepare($sqlUser);
			$stmtUser->bind_param("sss", $data['sdt'], $data['diaChi'], $maUser);
			$stmtUser->execute();
			$stmtUser->close();

			// 2. Cập nhật bảng nhatuyendung (chỉ sửa Website và Lĩnh vực)
			$sqlEmp = "UPDATE nhatuyendung SET Website = ?, LinhVuc = ? WHERE MaNhaTuyenDung = ?";
			$stmtEmp = $this->db->prepare($sqlEmp);
			$stmtEmp->bind_param("sss", $data['website'], $data['linhVuc'], $maUser);
			$stmtEmp->execute();
			$stmtEmp->close();

			$this->db->commit();
			return true;
		} catch (Exception $e) {
			$this->db->rollback();
			return false;
		}
	}
}
?>