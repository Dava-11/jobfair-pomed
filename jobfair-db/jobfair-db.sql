-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.28-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for jobfair_polmed
CREATE DATABASE IF NOT EXISTS `jobfair_polmed` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `jobfair_polmed`;

-- Dumping structure for table jobfair_polmed.admin_logs
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `action` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jobfair_polmed.admin_logs: ~0 rows (approximately)

-- Dumping structure for table jobfair_polmed.applications
CREATE TABLE IF NOT EXISTS `applications` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `status` enum('dikirim','diproses','diterima','ditolak') DEFAULT 'dikirim',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`application_id`),
  KEY `job_id` (`job_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jobfair_polmed.applications: ~1 rows (approximately)
INSERT INTO `applications` (`application_id`, `job_id`, `user_id`, `cv_file`, `alasan`, `status`, `applied_at`) VALUES
	(1, 5, 2, '../uploads/cv/CV MUHAMMAD DAVA KHAIRI NST.pdf', 'saya tertarik', 'dikirim', '2025-11-17 02:44:44');

-- Dumping structure for table jobfair_polmed.companies
CREATE TABLE IF NOT EXISTS `companies` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified') DEFAULT 'pending',
  PRIMARY KEY (`company_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jobfair_polmed.companies: ~9 rows (approximately)
INSERT INTO `companies` (`company_id`, `user_id`, `company_name`, `description`, `website`, `logo`, `status`) VALUES
	(1, 3, 'kiki enterprise', 'kiki enterprise adalah perusahaan yang bergerak di bidang IT', NULL, NULL, 'pending'),
	(2, 5, 'PT Bank Mandiri', 'Bank terbesar di Indonesia dengan fokus pada layanan perbankan digital dan inovasi teknologi finansial.', NULL, 'uploads/company_logos/logo_2_1763346870.png', 'pending'),
	(3, 6, 'PT Telkom Indonesia', 'Perusahaan telekomunikasi terdepan di Indonesia yang menyediakan layanan telekomunikasi, internet, dan teknologi informasi.', NULL, 'uploads/company_logos/logo_3_1763346975.png', 'pending'),
	(4, 7, 'PT Astra International', 'Perusahaan holding terbesar di Indonesia dengan bisnis di berbagai sektor termasuk otomotif, agribisnis, dan teknologi.', NULL, 'uploads/company_logos/logo_4_1763347102.png', 'pending'),
	(5, 8, 'PT Unilever Indonesia', 'Perusahaan consumer goods terkemuka yang memproduksi berbagai produk rumah tangga dan personal care.', NULL, 'uploads/company_logos/logo_5_1763347161.png', 'pending'),
	(6, 9, 'PT Indofood Sukses Makmur', 'Perusahaan makanan terbesar di Indonesia dengan portofolio produk makanan dan minuman yang luas.', NULL, 'uploads/company_logos/logo_6_1763347344.png', 'pending'),
	(7, 10, 'PT Pertamina', 'Perusahaan energi nasional yang bergerak di bidang minyak, gas, dan energi terbarukan.', NULL, 'uploads/company_logos/logo_7_1763347228.png', 'pending'),
	(8, 11, 'PT Bank Central Asia', 'Bank swasta terbesar di Indonesia dengan fokus pada layanan perbankan retail dan corporate.', NULL, 'uploads/company_logos/logo_8_1763346634.png', 'pending'),
	(9, 12, 'PT Gojek Indonesia', 'Perusahaan teknologi yang menyediakan layanan on-demand dan platform digital untuk berbagai kebutuhan sehari-hari.', NULL, 'uploads/company_logos/logo_9_1763346167.png', 'pending');

-- Dumping structure for table jobfair_polmed.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`job_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jobfair_polmed.jobs: ~13 rows (approximately)
INSERT INTO `jobs` (`job_id`, `company_id`, `title`, `description`, `requirements`, `location`, `deadline`, `created_at`) VALUES
	(1, 1, 'Front End Web Developer', 'buat ui web', 'D4', 'Medan', '2025-11-30', '2025-11-12 18:09:56'),
	(2, 2, 'Software Developer', 'Kami mencari Software Developer yang berpengalaman untuk mengembangkan aplikasi perbankan digital. Kandidat akan bekerja dalam tim agile dan menggunakan teknologi terbaru.', 'Minimal S1 Teknik Informatika/Ilmu Komputer, Menguasai Java/Python/JavaScript, Pengalaman 2+ tahun, Familiar dengan framework modern', 'Jakarta', '2025-12-17', '2025-11-17 01:51:07'),
	(3, 2, 'Data Analyst', 'Bergabunglah dengan tim data analytics untuk menganalisis data nasabah dan membantu pengambilan keputusan bisnis strategis.', 'S1 Statistika/Matematika/Informatika, Menguasai SQL, Python/R, Tableau/Power BI, Pengalaman 1+ tahun', 'Jakarta', '2025-12-12', '2025-11-17 01:51:07'),
	(4, 3, 'Network Engineer', 'Bertanggung jawab untuk merancang, mengimplementasikan, dan memelihara infrastruktur jaringan telekomunikasi.', 'S1 Teknik Telekomunikasi/Informatika, CCNA/CCNP certified, Pengalaman 3+ tahun, Menguasai routing dan switching', 'Bandung', '2025-12-22', '2025-11-17 01:51:07'),
	(5, 3, 'UI/UX Designer', 'Merancang antarmuka pengguna yang menarik dan user-friendly untuk aplikasi mobile dan web perusahaan.', 'S1 Desain Komunikasi Visual/Informatika, Menguasai Figma/Adobe XD, Portfolio yang kuat, Pengalaman 2+ tahun', 'Jakarta', '2025-12-15', '2025-11-17 01:51:07'),
	(6, 4, 'Mechanical Engineer', 'Merancang dan mengembangkan komponen kendaraan bermotor dengan fokus pada inovasi dan efisiensi.', 'S1 Teknik Mesin, Pengalaman 2+ tahun di industri otomotif, Menguasai CAD/CAM, Kemampuan analitis yang kuat', 'Jakarta', '2025-12-27', '2025-11-17 01:51:07'),
	(7, 5, 'Marketing Specialist', 'Mengembangkan strategi pemasaran untuk produk consumer goods dan mengelola kampanye digital marketing.', 'S1 Marketing/Bisnis, Pengalaman 2+ tahun di marketing, Kreatif dan analitis, Menguasai digital marketing tools', 'Jakarta', '2025-12-07', '2025-11-17 01:51:07'),
	(8, 6, 'Quality Assurance Engineer', 'Memastikan kualitas produk makanan sesuai standar dengan melakukan pengujian dan monitoring proses produksi.', 'S1 Teknik Industri/Teknologi Pangan, Pengalaman 1+ tahun, Detail-oriented, Menguasai HACCP/GMP', 'Bekasi', '2025-12-17', '2025-11-17 01:51:07'),
	(9, 7, 'Petroleum Engineer', 'Merancang dan mengoptimalkan proses produksi minyak dan gas dengan fokus pada efisiensi dan keselamatan.', 'S1 Teknik Perminyakan/Teknik Kimia, Pengalaman 3+ tahun, Menguasai software engineering, Strong analytical skills', 'Jakarta', '2026-01-01', '2025-11-17 01:51:07'),
	(10, 8, 'Backend Developer', 'Mengembangkan dan memelihara sistem backend untuk aplikasi perbankan dengan fokus pada keamanan dan performa.', 'S1 Teknik Informatika, Menguasai Node.js/Java/Python, Database design, Pengalaman 2+ tahun, API development', 'Jakarta', '2025-12-12', '2025-11-17 01:51:07'),
	(11, 9, 'Mobile App Developer', 'Mengembangkan aplikasi mobile untuk platform Gojek dengan teknologi React Native atau Flutter.', 'S1 Teknik Informatika, Menguasai React Native/Flutter, Pengalaman 2+ tahun, Portfolio aplikasi mobile', 'Jakarta', '2025-12-17', '2025-11-17 01:51:07'),
	(12, 9, 'Product Manager', 'Mengelola produk digital dari konsep hingga peluncuran, bekerja sama dengan tim engineering dan design.', 'S1 Teknik/Bisnis, Pengalaman 3+ tahun sebagai PM, Strong analytical dan communication skills, Tech-savvy', 'Jakarta', '2025-12-22', '2025-11-17 01:51:07'),
	(13, 2, 'Cybersecurity Specialist', 'Melindungi sistem informasi bank dari ancaman cyber dengan melakukan monitoring, analisis, dan response terhadap insiden keamanan.', 'S1 Teknik Informatika/Siber, Sertifikasi CISSP/CEH, Pengalaman 3+ tahun, Pengetahuan mendalam tentang security', 'Jakarta', '2025-12-17', '2025-11-17 01:51:07');

-- Dumping structure for table jobfair_polmed.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','perusahaan','admin') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jobfair_polmed.users: ~13 rows (approximately)
INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `phone`, `address`, `created_at`) VALUES
	(1, 'Alex Ramirez', 'ramirez123@gmail.com', '$2y$10$C8G9XZfAiAjTEEroSB.WlOK8e36OuMrhyVKSXsn8ceypvvqkb75DW', 'mahasiswa', NULL, NULL, '2025-11-12 18:01:30'),
	(2, 'alex', 'alex@gmail.com', '$2y$10$4ZPA.Ctx./UlDtOjm5Ck5.TdgmACRwV6nzOF20a0r/ZtQFtWRCLlG', 'mahasiswa', '081263428682', NULL, '2025-11-12 18:02:33'),
	(3, 'kiki', 'kiki@gmail.com', '$2y$10$p22qX7Nnr3akw6qGx59fwu8duv6Bb2vbDWyACOaAAVOZST8OpjVnu', 'perusahaan', NULL, NULL, '2025-11-12 18:08:44'),
	(4, 'Administrator', 'admin@polmed.ac.id', '$2y$10$NnZqJHiBHBlMyEvQgqp1MuSMjN3zHlNu53qwdDQMpaWQJBN22d6rm', 'admin', NULL, NULL, '2025-11-13 02:31:44'),
	(5, 'HRD PT Bank Mandiri', 'hrd@bankmandiri.co.id', '$2y$10$A3eutmKymtqJFz6gtbTTMe446/zzK3XeqAl/MUxXSmU.yOK2WCAGa', 'perusahaan', '0215551234', NULL, '2025-11-17 01:51:07'),
	(6, 'Recruiter PT Telkom Indonesia', 'recruitment@telkom.co.id', '$2y$10$A3eutmKymtqJFz6gtbTTMe446/zzK3XeqAl/MUxXSmU.yOK2WCAGa', 'perusahaan', '0215551235', NULL, '2025-11-17 01:51:07'),
	(7, 'HR PT Astra International', 'hr@astra.co.id', '$2y$10$A3eutmKymtqJFz6gtbTTMe446/zzK3XeqAl/MUxXSmU.yOK2WCAGa', 'perusahaan', '0215551236', NULL, '2025-11-17 01:51:07'),
	(8, 'Talent Acquisition PT Unilever', 'talent@unilever.co.id', '$2y$10$A3eutmKymtqJFz6gtbTTMe446/zzK3XeqAl/MUxXSmU.yOK2WCAGa', 'perusahaan', '0215551237', NULL, '2025-11-17 01:51:07'),
	(9, 'HR Manager PT Indofood', 'hr@indofood.co.id', '$2y$10$A3eutmKymtqJFz6gtbTTMe446/zzK3XeqAl/MUxXSmU.yOK2WCAGa', 'perusahaan', '0215551238', NULL, '2025-11-17 01:51:07'),
	(10, 'Recruitment PT Pertamina', 'recruitment@pertamina.com', '$2y$10$A3eutmKymtqJFz6gtbTTMe446/zzK3XeqAl/MUxXSmU.yOK2WCAGa', 'perusahaan', '0215551239', NULL, '2025-11-17 01:51:07'),
	(11, 'HR PT Bank BCA', 'hr@bca.co.id', '$2y$10$A3eutmKymtqJFz6gtbTTMe446/zzK3XeqAl/MUxXSmU.yOK2WCAGa', 'perusahaan', '0215551240', NULL, '2025-11-17 01:51:07'),
	(12, 'Talent PT Gojek', 'talent@gojek.com', '$2y$10$A3eutmKymtqJFz6gtbTTMe446/zzK3XeqAl/MUxXSmU.yOK2WCAGa', 'perusahaan', '0215551241', NULL, '2025-11-17 01:51:07'),
	(13, 'Muhammad riski ', 'riski@gmail.com', '$2y$10$H.w7pbvTPlPwlnXz5giu1u5FPVUtKYe7X/3iP5MCwaG9zTH4eLZ96', 'mahasiswa', NULL, NULL, '2025-12-03 16:29:47');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
