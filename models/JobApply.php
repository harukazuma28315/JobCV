<?php

require_once __DIR__ . '/../config/db.php';

class JobApply
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Kiểm tra ứng viên đã ứng tuyển tin này chưa
     */
    public function hasApplied($maCV, $maTinTuyenDung)
    {
        $sql = "
            SELECT MaHS
            FROM hosotuyendung
            WHERE MaCV = ?
            AND MaTinTuyenDung = ?
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }

        $stmt->bind_param(
            "ss",
            $maCV,
            $maTinTuyenDung
        );

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    /**
     * Tạo hồ sơ ứng tuyển
     */
    public function create(
        $maHS,
        $maCV,
        $maTinTuyenDung,
        $coverLetter = null
    ) {
        $sql = "
            INSERT INTO hosotuyendung
            (
                MaHS,
                MaCV,
                MaTinTuyenDung,
                CoverLetter,
                TrangThai
            )
            VALUES (?, ?, ?, ?, 'MoiNop')
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }

        $stmt->bind_param(
            "ssss",
            $maHS,
            $maCV,
            $maTinTuyenDung,
            $coverLetter
        );

        return $stmt->execute();
    }

    /**
     * Sinh mã hồ sơ mới
     */
    /**
 * Sinh mã hồ sơ mới (không trùng)
 */
    public function generateId()
    {
        // Lấy số lớn nhất hiện có (xử lý đúng kiểu số, không phụ thuộc sắp xếp chuỗi)
        $sql = "
            SELECT MAX(CAST(SUBSTRING(MaHS, 3) AS UNSIGNED)) AS maxNum
            FROM hosotuyendung
            WHERE MaHS LIKE 'HS%'
        ";

        $result = $this->conn->query($sql);
        $row = $result ? $result->fetch_assoc() : null;

        $next = ((int)($row['maxNum'] ?? 0)) + 1;

        // Đảm bảo không trùng (phòng trường hợp race condition)
        do {
            $maHS = 'HS' . str_pad($next, 3, '0', STR_PAD_LEFT);

            $check = $this->conn->prepare("SELECT MaHS FROM hosotuyendung WHERE MaHS = ? LIMIT 1");
            $check->bind_param("s", $maHS);
            $check->execute();
            $exists = $check->get_result()->num_rows > 0;
            $check->close();

            if ($exists) {
                $next++;
            }
        } while ($exists);

        return $maHS;
    }
    
    public function getApplicationsByCandidate($maUngVien)
    {
        $sql = "
            SELECT
                hs.MaHS,
                hs.MaCV,
                hs.MaTinTuyenDung,
                hs.NgayNop,
                hs.CoverLetter,
                hs.TrangThai,

                cv.TenFileCV,
                cv.DuongDanFileCV,

                ttd.TieuDe,

                ntd.TenCongTy

            FROM hosotuyendung hs

            INNER JOIN cv
                ON hs.MaCV = cv.MaCV

            INNER JOIN tintuyendung ttd
                ON hs.MaTinTuyenDung = ttd.MaTinTuyenDung

            INNER JOIN nhatuyendung ntd
                ON ttd.MaNhaTuyenDung = ntd.MaNhaTuyenDung

            WHERE cv.MaUngVien = ?

            ORDER BY hs.NgayNop DESC
        ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }

        $stmt->bind_param("s", $maUngVien);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}