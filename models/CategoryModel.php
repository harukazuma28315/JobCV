<?php
/**
 * File: app/models/CategoryModel.php
 * Sử dụng bảng danhmuc có sẵn trong CSDL
 */

require_once ROOT_PATH . '/config/db.php';

class CategoryModel {
	private $link;

	public function __construct() {
		$this->link = Database::getConnection();
	}

	/**
	 * Lấy danh sách các danh mục thuộc loại "Ngành nghề".
	 *
	 * @return array
	 */
	public function getIndustryList() {
		$sql = "SELECT * FROM danhmuc WHERE LoaiDanhMuc = 'nganhnghe' ORDER BY NgayTao DESC";
		$result = mysqli_query($this->link, $sql);
		$list = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$list[] = $row;
		}
		return $list;
	}

	/**
	 * Lấy danh sách các danh mục thuộc loại "Địa điểm".
	 *
	 * @return array
	 */
	public function getLocationList() {
		$sql = "SELECT * FROM danhmuc WHERE LoaiDanhMuc = 'diadiem' ORDER BY NgayTao DESC";
		$result = mysqli_query($this->link, $sql);
		$list = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$list[] = $row;
		}
		return $list;
	}

	/**
	 * Thêm một ngành nghề mới vào bảng danhmuc (LoaiDanhMuc cố định = 'nganhnghe').
	 *
	 * @param string $tenNganh Tên ngành nghề hiển thị
	 * @param string $maNganh  Mã ngành nghề (khóa chính)
	 * @return bool
	 */
	public function addIndustry($tenNganh, $maNganh) {
		$sql = "INSERT INTO danhmuc (MaDanhMuc, TenDanhMuc, LoaiDanhMuc, NgayTao) 
				VALUES (?, ?, 'nganhnghe', NOW())";
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'ss', $maNganh, $tenNganh);
		$success = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $success;
	}

	/**
	 * Thêm một địa điểm mới vào bảng danhmuc (LoaiDanhMuc cố định = 'diadiem').
	 *
	 * @param string $tenDiaDiem Tên địa điểm hiển thị
	 * @param string $maVung     Mã địa điểm (khóa chính)
	 * @return bool
	 */
	public function addLocation($tenDiaDiem, $maVung) {
		$sql = "INSERT INTO danhmuc (MaDanhMuc, TenDanhMuc, LoaiDanhMuc, NgayTao) 
				VALUES (?, ?, 'diadiem', NOW())";
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'ss', $maVung, $tenDiaDiem);
		$success = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $success;
	}

	/**
	 * Xóa một danh mục theo mã, áp dụng cho cả 2 loại (ngành nghề/địa điểm).
	 *
	 * @param string $maDanhMuc
	 * @return bool
	 */
	public function deleteCategoryItem($maDanhMuc) {
		$sql = "DELETE FROM danhmuc WHERE MaDanhMuc = ?";
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 's', $maDanhMuc);
		$success = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $success;
	}

	/**
	 * Sinh mã danh mục mới, không trùng với mã đã có trong bảng.
	 * Tiền tố theo loại: DM_NN (ngành nghề) / DM_DD (địa điểm).
	 *
	 * @param string $loai 'nganhnghe' hoặc 'diadiem'
	 * @return string Mã danh mục chưa tồn tại trong bảng
	 */
	private function generateMaDanhMuc($loai) {
		$prefix = ($loai === 'diadiem') ? 'DM_DD' : 'DM_NN';

		do {
			$ma = $prefix . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));

			$sql = "SELECT 1 FROM danhmuc WHERE MaDanhMuc = ? LIMIT 1";
			$stmt = mysqli_prepare($this->link, $sql);
			mysqli_stmt_bind_param($stmt, 's', $ma);
			mysqli_stmt_execute($stmt);
			$exists = mysqli_stmt_get_result($stmt) && mysqli_stmt_num_rows($stmt) > 0;
			mysqli_stmt_close($stmt);
		} while ($exists);

		return $ma;
	}

	/**
	 * Thêm một bản ghi danh mục dùng chung cho mọi loại. Mã danh mục (MaDanhMuc)
	 * được hệ thống tự sinh, admin không cần tự nhập.
	 *
	 * @param string $ten  Tên danh mục hiển thị
	 * @param string $loai Loại danh mục: 'nganhnghe' hoặc 'diadiem'
	 * @return string|false Mã danh mục vừa tạo, hoặc false nếu thêm thất bại
	 */
	public function addCategoryItem($ten, $loai) {
		$ma = $this->generateMaDanhMuc($loai);

		$sql = "INSERT INTO danhmuc (MaDanhMuc, TenDanhMuc, LoaiDanhMuc, NgayTao) 
				VALUES (?, ?, ?, NOW())";
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'sss', $ma, $ten, $loai);
		$success = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		return $success ? $ma : false;
	}

	/**
	 * Cập nhật một danh mục dùng chung cho mọi loại, cho phép đổi cả mã (MaDanhMuc).
	 *
	 * @param string $oldMa Mã danh mục hiện tại (dùng để xác định bản ghi cần sửa)
	 * @param string $newMa Mã danh mục mới
	 * @param string $ten   Tên danh mục mới
	 * @param string $loai  Loại danh mục: 'nganhnghe' hoặc 'diadiem'
	 * @return bool
	 */
	public function updateCategoryItem($oldMa, $newMa, $ten, $loai) {
		$sql = "UPDATE danhmuc 
				SET MaDanhMuc = ?, TenDanhMuc = ?, LoaiDanhMuc = ? 
				WHERE MaDanhMuc = ?";
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'ssss', $newMa, $ten, $loai, $oldMa);
		$success = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $success;
	}
}