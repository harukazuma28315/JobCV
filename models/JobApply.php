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
}