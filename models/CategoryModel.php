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
	 * Thêm một bản ghi danh mục dùng chung cho mọi loại (hàm tổng quát hơn
	 * addIndustry/addLocation, dùng khi loại danh mục được truyền động từ form admin).
	 *
	 * @param string $ten  Tên danh mục hiển thị
	 * @param string $ma   Mã danh mục (khóa chính)
	 * @param string $loai Loại danh mục: 'nganhnghe' hoặc 'diadiem'
	 * @return bool
	 */
	public function addCategoryItem($ten, $ma, $loai) {
		$sql = "INSERT INTO danhmuc (MaDanhMuc, TenDanhMuc, LoaiDanhMuc, NgayTao) 
				VALUES (?, ?, ?, NOW())";
		$stmt = mysqli_prepare($this->link, $sql);
		mysqli_stmt_bind_param($stmt, 'sss', $ma, $ten, $loai);
		$success = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		return $success;
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

