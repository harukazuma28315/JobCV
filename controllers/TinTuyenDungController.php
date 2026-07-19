<?php

require_once __DIR__ . "/../models/TinTuyenDung.php";

/**
 * Controller quản lý tin tuyển dụng.
 */
class TinTuyenDungController
{
	private $tinTuyenDungModel;

	public function __construct()
	{
		$this->tinTuyenDungModel = new TinTuyenDung();
	}

	/**
	 * Lấy danh sách tin tuyển dụng.
	 *
	 * @return mysqli_result
	 */
	public function index()
	{
		return $this->tinTuyenDungModel->getAll();
	}

	/**
	 * Lấy thông tin chi tiết tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @return array|null
	 */
	public function detail($maTinTuyenDung)
	{
		return $this->tinTuyenDungModel->getById($maTinTuyenDung);
	}

	/**
	 * Đăng tin tuyển dụng mới.
	 *
	 * @param array $tinTuyenDungData
	 * @return bool
	 */
	public function create(array $tinTuyenDungData)
	{
		if (!$this->validateJobData($tinTuyenDungData)) {
			return false;
		}

		return $this->tinTuyenDungModel->create($tinTuyenDungData);
	}

	/**
	 * Cập nhật nội dung tin tuyển dụng.
	 *
	 * @param array $tinTuyenDungData
	 * @return bool
	 */
	public function update(array $tinTuyenDungData)
	{
		if (!$this->validateJobData($tinTuyenDungData)) {
			return false;
		}

		return $this->tinTuyenDungModel->update($tinTuyenDungData);
	}

	/**
	 * Xóa tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @return bool
	 */
	public function delete($maTinTuyenDung)
	{
		return $this->tinTuyenDungModel->delete($maTinTuyenDung);
	}

	/**
	 * Gia hạn thời gian tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @param string $ngayHetHan
	 * @return bool
	 */
	public function extendDeadline($maTinTuyenDung, $ngayHetHan)
	{
		if (empty($ngayHetHan)) {
			return false;
		}

		return $this->tinTuyenDungModel->extendDeadline(
			$maTinTuyenDung,
			$ngayHetHan
		);
	}

	/**
	 * Đóng tin tuyển dụng.
	 *
	 * @param string $maTinTuyenDung
	 * @return bool
	 */
	public function closeJob($maTinTuyenDung)
	{
		return $this->tinTuyenDungModel->closeJob($maTinTuyenDung);
	}

	/**
	 * Kiểm tra dữ liệu tin tuyển dụng.
	 *
	 * @param array $tinTuyenDungData
	 * @return bool
	 */
	private function validateJobData(array $tinTuyenDungData)
	{
		if (empty(trim($tinTuyenDungData["tieuDe"]))) {
			return false;
		}

		if (empty(trim($tinTuyenDungData["moTaCongViec"]))) {
			return false;
		}

		if (empty(trim($tinTuyenDungData["yeuCauCongViec"]))) {
			return false;
		}

		if (empty(trim($tinTuyenDungData["viTriTuyenDung"]))) {
			return false;
		}

		if (empty($tinTuyenDungData["ngayHetHan"])) {
			return false;
		}

		if ($tinTuyenDungData["soLuongTuyen"] <= 0) {
			return false;
		}

		return true;
	}
}