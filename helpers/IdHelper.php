<?php
/**
 * File: app/helpers/IdHelper.php
 * Đường dẫn: Module5_UngTuyenVaQuanLyHoSo/helpers/IdHelper.php
 * Chức năng: Sinh khóa chính duy nhất (varchar) cho các bảng không dùng
 *            AUTO_INCREMENT (hosotuyendung.MaHS,...), theo đúng thiết kế CSDL hiện có.
 */

class IdHelper
{
	/**
	 * Sinh ID duy nhất dạng: PREFIX + thời gian (microtime) + số ngẫu nhiên.
	 * Dùng uniqid với more_entropy để giảm tối đa khả năng trùng lặp
	 * khi nhiều request submit gần như đồng thời.
	 *
	 * @param string $prefix Tiền tố nhận diện loại bản ghi, ví dụ: 'HS'
	 * @return string Chuỗi ID có độ dài phù hợp với cột varchar(50)
	 */
	public static function generate($prefix)
	{
		$raw = uniqid('', true);
		$clean = str_replace('.', '', $raw);

		return strtoupper($prefix) . '_' . $clean;
	}
}