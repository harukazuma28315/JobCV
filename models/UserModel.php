<?php
class UserModel {
    private $db;

    // Truyền kết nối $conn vào khi khởi tạo
    public function __construct($databaseConnection) {
        $this->db = $databaseConnection;
    }

    // Kiểm tra tài khoản đã tồn tại chưa
    public function isUsernameExists($taiKhoan) {
        $stmt = $this->db->prepare("SELECT TaiKhoan FROM user WHERE TaiKhoan = ?");
        $stmt->bind_param("s", $taiKhoan);
        $stmt->execute();
        $stmt->store_result();
        
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        
        return $exists;
    }

    // Thêm user mới vào database
    public function registerUser($data) {
        $sql = "INSERT INTO user (MaUser, TaiKhoan, MatKhau, Role, HoTen, NgaySinh, GioiTinh, Email, SDT, DiaChi) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssississs", 
            $data['maUser'], 
            $data['taiKhoan'], 
            $data['matKhauHashed'], 
            $data['role'], 
            $data['hoTen'], 
            $data['ngaySinh'], 
            $data['gioiTinh'], 
            $data['email'], 
            $data['sdt'], 
            $data['diaChi']
        );

        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}
?>