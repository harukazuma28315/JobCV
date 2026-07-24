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
    public function generateId()
    {
        $sql = "
            SELECT MaHS
            FROM hosotuyendung
            ORDER BY MaHS DESC
            LIMIT 1
        ";

        $result = $this->conn->query($sql);

        if ($result->num_rows === 0) {
            return 'HS001';
        }

        $row = $result->fetch_assoc();

        $number = intval(substr($row['MaHS'], 2)) + 1;

        return 'HS' . str_pad($number, 3, '0', STR_PAD_LEFT);
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