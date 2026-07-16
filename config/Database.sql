-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 13, 2026 lúc 09:59 AM
-- Phiên bản máy phục vụ: 10.1.32-MariaDB
-- Phiên bản PHP: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cvdb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `MaAdmin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdanhmuc`
--

CREATE TABLE `chitietdanhmuc` (
  `MaCTDM` varchar(50) NOT NULL,
  `MaTinTuyenDung` varchar(50) NOT NULL,
  `MaDanhMuc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chungchi`
--

CREATE TABLE `chungchi` (
  `MaChungChi` varchar(50) NOT NULL,
  `MaCV` varchar(50) NOT NULL,
  `TenChungChi` varchar(200) NOT NULL,
  `ToChucCap` varchar(200) DEFAULT NULL,
  `NgayCap` date DEFAULT NULL,
  `NgayHetHan` date DEFAULT NULL,
  `MaSoChungChi` varchar(50) DEFAULT NULL,
  `DuongLinkChungChi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cv`
--

CREATE TABLE `cv` (
  `MaCV` varchar(50) NOT NULL,
  `MaUngVien` varchar(50) NOT NULL,
  `TieuDe` varchar(200) NOT NULL,
  `KyNang` text,
  `SoThich` varchar(255) DEFAULT NULL,
  `MucTieu` text,
  `TrangThai` tinyint(1) DEFAULT '1',
  `ViTriMongMuon` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDanhMuc` varchar(50) NOT NULL,
  `TenDanhMuc` varchar(100) NOT NULL,
  `LoaiDanhMuc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `duan`
--

CREATE TABLE `duan` (
  `MaDuAn` varchar(50) NOT NULL,
  `MaCV` varchar(50) NOT NULL,
  `TenDuAn` varchar(200) NOT NULL,
  `ViTri` varchar(100) DEFAULT NULL,
  `SoLuongThanhVien` int(11) DEFAULT NULL,
  `CongNgheSuDung` varchar(255) DEFAULT NULL,
  `MoTa` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hocvan`
--

CREATE TABLE `hocvan` (
  `MaHocVan` varchar(50) NOT NULL,
  `MaCV` varchar(50) NOT NULL,
  `TenTruong` varchar(200) NOT NULL,
  `ChuyenNganh` varchar(100) DEFAULT NULL,
  `HocLuc` varchar(50) DEFAULT NULL,
  `GPA` varchar(10) DEFAULT NULL,
  `NamBatDau` year(4) DEFAULT NULL,
  `NamKetThuc` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hosotuyendung`
--

CREATE TABLE `hosotuyendung` (
  `MaHS` varchar(50) NOT NULL,
  `MaCV` varchar(50) NOT NULL,
  `MaTinTuyenDung` varchar(50) NOT NULL,
  `NgayNop` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CoverLetter` text COMMENT 'Thư giới thiệu dạng văn bản do ứng viên nhập (optional)',
  `CoverLetterFile` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn file Cover Letter đính kèm (optional)',
  `TrangThai` enum('MoiNop','DaXem','HenPhongVan','NhanViec','TuChoi') DEFAULT 'MoiNop',
  `NgayCapNhatTrangThai` datetime DEFAULT NULL COMMENT 'Thời điểm trạng thái được cập nhật gần nhất'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kinhnghiemlamviec`
--

CREATE TABLE `kinhnghiemlamviec` (
  `MaCongViec` varchar(50) NOT NULL,
  `MaCV` varchar(50) NOT NULL,
  `TenCongTy` varchar(200) NOT NULL,
  `ViTri` varchar(100) NOT NULL,
  `ThoiGianLamViec` varchar(50) DEFAULT NULL,
  `MoTa` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaNhanVien` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhatuyendung`
--

CREATE TABLE `nhatuyendung` (
  `MaNhaTuyenDung` varchar(50) NOT NULL,
  `TenCongTy` varchar(200) NOT NULL,
  `Website` varchar(100) DEFAULT NULL,
  `LinhVuc` varchar(100) DEFAULT NULL,
  `MaSoThue` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tintuyendung`
--

CREATE TABLE `tintuyendung` (
  `MaTinTuyenDung` varchar(50) NOT NULL,
  `MaNhaTuyenDung` varchar(50) NOT NULL,
  `TieuDe` varchar(200) NOT NULL,
  `MoTaCongViec` text,
  `NgayDang` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `NgayHetHan` date DEFAULT NULL,
  `YeuCauCongViec` text,
  `ViTriTuyenDung` varchar(100) DEFAULT NULL,
  `MucLuong` decimal(15,2) DEFAULT NULL,
  `DiaChiLamViec` varchar(255) DEFAULT NULL,
  `HinhThucLamViec` varchar(50) DEFAULT NULL,
  `DoTuoiYeuCau` varchar(50) DEFAULT NULL,
  `SoLuongTuyen` int(11) DEFAULT NULL,
  `ThoiGianThuViec` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ungvien`
--

CREATE TABLE `ungvien` (
  `MaUngVien` varchar(50) NOT NULL,
  `TrangThaiTimViec` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `MaUser` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL, -- Đổi thành NOT NULL để làm tài khoản đăng nhập chính
  `MatKhau` varchar(255) NOT NULL,
  `Role` int(11) NOT NULL DEFAULT '0' COMMENT '0 = UngVien, 1 = NhaTuyenDung, 2 = Admin',
  `HoTen` varchar(100) NOT NULL, -- Trường này lưu tên ứng viên HOẶC tên công ty
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` tinyint(1) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`MaAdmin`);

--
-- Chỉ mục cho bảng `chitietdanhmuc`
--
ALTER TABLE `chitietdanhmuc`
  ADD PRIMARY KEY (`MaCTDM`),
  ADD KEY `fk_ctdm_tintuyen` (`MaTinTuyenDung`),
  ADD KEY `fk_ctdm_danhmuc` (`MaDanhMuc`);

--
-- Chỉ mục cho bảng `chungchi`
--
ALTER TABLE `chungchi`
  ADD PRIMARY KEY (`MaChungChi`),
  ADD KEY `fk_chungchi_cv` (`MaCV`);

--
-- Chỉ mục cho bảng `cv`
--
ALTER TABLE `cv`
  ADD PRIMARY KEY (`MaCV`),
  ADD KEY `idx_cv_ungvien` (`MaUngVien`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDanhMuc`);

--
-- Chỉ mục cho bảng `duan`
--
ALTER TABLE `duan`
  ADD PRIMARY KEY (`MaDuAn`),
  ADD KEY `fk_duan_cv` (`MaCV`);

--
-- Chỉ mục cho bảng `hocvan`
--
ALTER TABLE `hocvan`
  ADD PRIMARY KEY (`MaHocVan`),
  ADD KEY `fk_hocvan_cv` (`MaCV`);

--
-- Chỉ mục cho bảng `hosotuyendung`
--
ALTER TABLE `hosotuyendung`
  ADD PRIMARY KEY (`MaHS`),
  ADD UNIQUE KEY `uk_hoso_cv_tin` (`MaCV`,`MaTinTuyenDung`),
  ADD KEY `idx_hoso_cv` (`MaCV`),
  ADD KEY `idx_hoso_tintuyen` (`MaTinTuyenDung`);

--
-- Chỉ mục cho bảng `kinhnghiemlamviec`
--
ALTER TABLE `kinhnghiemlamviec`
  ADD PRIMARY KEY (`MaCongViec`),
  ADD KEY `fk_kinhnghiem_cv` (`MaCV`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaNhanVien`);

--
-- Chỉ mục cho bảng `nhatuyendung`
--
ALTER TABLE `nhatuyendung`
  ADD PRIMARY KEY (`MaNhaTuyenDung`);

--
-- Chỉ mục cho bảng `tintuyendung`
--
ALTER TABLE `tintuyendung`
  ADD PRIMARY KEY (`MaTinTuyenDung`),
  ADD KEY `idx_tintuyen_nhatuyendung` (`MaNhaTuyenDung`);

--
-- Chỉ mục cho bảng `ungvien`
--
ALTER TABLE `ungvien`
  ADD PRIMARY KEY (`MaUngVien`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`MaUser`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `idx_user_taikhoan` (`MaUser`);
  
--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_nhanvien` FOREIGN KEY (`MaAdmin`) REFERENCES `nhanvien` (`MaNhanVien`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chitietdanhmuc`
--
ALTER TABLE `chitietdanhmuc`
  ADD CONSTRAINT `fk_ctdm_danhmuc` FOREIGN KEY (`MaDanhMuc`) REFERENCES `danhmuc` (`MaDanhMuc`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ctdm_tintuyen` FOREIGN KEY (`MaTinTuyenDung`) REFERENCES `tintuyendung` (`MaTinTuyenDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chungchi`
--
ALTER TABLE `chungchi`
  ADD CONSTRAINT `fk_chungchi_cv` FOREIGN KEY (`MaCV`) REFERENCES `cv` (`MaCV`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cv`
--
ALTER TABLE `cv`
  ADD CONSTRAINT `fk_cv_ungvien` FOREIGN KEY (`MaUngVien`) REFERENCES `ungvien` (`MaUngVien`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `duan`
--
ALTER TABLE `duan`
  ADD CONSTRAINT `fk_duan_cv` FOREIGN KEY (`MaCV`) REFERENCES `cv` (`MaCV`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hocvan`
--
ALTER TABLE `hocvan`
  ADD CONSTRAINT `fk_hocvan_cv` FOREIGN KEY (`MaCV`) REFERENCES `cv` (`MaCV`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hosotuyendung`
--
ALTER TABLE `hosotuyendung`
  ADD CONSTRAINT `fk_hoso_cv` FOREIGN KEY (`MaCV`) REFERENCES `cv` (`MaCV`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_hoso_tintuyen` FOREIGN KEY (`MaTinTuyenDung`) REFERENCES `tintuyendung` (`MaTinTuyenDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `kinhnghiemlamviec`
--
ALTER TABLE `kinhnghiemlamviec`
  ADD CONSTRAINT `fk_kinhnghiem_cv` FOREIGN KEY (`MaCV`) REFERENCES `cv` (`MaCV`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `fk_nhanvien_user` FOREIGN KEY (`MaNhanVien`) REFERENCES `user` (`MaUser`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `nhatuyendung`
--
ALTER TABLE `nhatuyendung`
  ADD CONSTRAINT `fk_nhatuyendung_user` FOREIGN KEY (`MaNhaTuyenDung`) REFERENCES `user` (`MaUser`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tintuyendung`
--
ALTER TABLE `tintuyendung`
  ADD CONSTRAINT `fk_tintuyen_nhatuyendung` FOREIGN KEY (`MaNhaTuyenDung`) REFERENCES `nhatuyendung` (`MaNhaTuyenDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ungvien`
--
ALTER TABLE `ungvien`
  ADD CONSTRAINT `fk_ungvien_user` FOREIGN KEY (`MaUngVien`) REFERENCES `user` (`MaUser`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
