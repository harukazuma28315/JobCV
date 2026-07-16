<?php

require_once __DIR__ . "/../models/NhaTuyenDung.php";

/**
 * Controller quản lý thông tin nhà tuyển dụng.
 */
class NhaTuyenDungController
{
	private $nhaTuyenDungModel;

	public function __construct()
	{
		$this->nhaTuyenDungModel = new NhaTuyenDung();
	}

	/**
	 * Lấy thông tin doanh nghiệp.
	 *
	 * @param string $maNhaTuyenDung
	 * @return array|null
	 */
	public function detail($maNhaTuyenDung)
	{
		return $this->nhaTuyenDungModel->getById($maNhaTuyenDung);
	}

	/**
	 * Cập nhật thông tin doanh nghiệp.
	 *
	 * @param array $nhaTuyenDungData
	 * @return bool
	 */
	public function update(array $nhaTuyenDungData)
	{
		return $this->nhaTuyenDungModel->update(
			$nhaTuyenDungData
		);
	}

	/**
	 * Upload logo doanh nghiệp.
	 *
	 * @param string $maNhaTuyenDung
	 * @param array $file
	 * @return bool
	 */
	public function uploadLogo($maNhaTuyenDung, $file)
	{
		if ($file["error"] != 0) {
			return false;
		}

		$extension = strtolower(
			pathinfo(
				$file["name"],
				PATHINFO_EXTENSION
			)
		);

		$allowExtensions = [
			"jpg",
			"jpeg",
			"png"
		];

		if (!in_array($extension, $allowExtensions)) {
			return false;
		}

		$directory = __DIR__ . "/../uploads/logo/";

		if (!is_dir($directory)) {
			mkdir($directory, 0777, true);
		}

		$fileName = $maNhaTuyenDung . "." . $extension;

		$uploadPath = $directory . $fileName;

		if (!move_uploaded_file(
			$file["tmp_name"],
			$uploadPath
		)) {
			return false;
		}

		return $this->nhaTuyenDungModel->updateLogo(
			$maNhaTuyenDung,
			"uploads/logo/" . $fileName
		);
	}

	/**
	 * Upload ảnh bìa doanh nghiệp.
	 *
	 * @param string $maNhaTuyenDung
	 * @param array $file
	 * @return bool
	 */
	public function uploadCover($maNhaTuyenDung, $file)
	{
		if ($file["error"] != 0) {
			return false;
		}

		$extension = strtolower(
			pathinfo(
				$file["name"],
				PATHINFO_EXTENSION
			)
		);

		$allowExtensions = [
			"jpg",
			"jpeg",
			"png"
		];

		if (!in_array($extension, $allowExtensions)) {
			return false;
		}

		$directory = __DIR__ . "/../uploads/cover/";

		if (!is_dir($directory)) {
			mkdir($directory, 0777, true);
		}

		$fileName = $maNhaTuyenDung . "." . $extension;

		$uploadPath = $directory . $fileName;

		if (!move_uploaded_file(
			$file["tmp_name"],
			$uploadPath
		)) {
			return false;
		}

		return $this->nhaTuyenDungModel->updateCover(
			$maNhaTuyenDung,
			"uploads/cover/" . $fileName
		);
	}
}