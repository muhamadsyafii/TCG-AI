-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 13, 2026 at 08:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qa_generator`
--

-- --------------------------------------------------------

--
-- Table structure for table `test_cases`
--

CREATE TABLE `test_cases` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `test_type` enum('POSITIVE','NEGATIVE') NOT NULL,
  `tc_format` varchar(50) DEFAULT 'STANDARD',
  `title` varchar(255) NOT NULL,
  `precondition` text DEFAULT NULL,
  `data_test` text DEFAULT NULL,
  `steps` text NOT NULL,
  `expected_result` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_cases`
--

INSERT INTO `test_cases` (`id`, `project_name`, `test_type`, `tc_format`, `title`, `precondition`, `data_test`, `steps`, `expected_result`, `created_at`) VALUES
(55, 'LiveTracking', 'POSITIVE', 'BDD', 'Pengemudi Berhasil Memulai Pelacakan Pengiriman', 'Pengemudi sudah login ke aplikasi \'TrackMyDelivery\'. GPS aktif di perangkat. Koneksi internet stabil.', 'ID Pengiriman: TD-20231225-001 (status \'Menunggu Penjemputan\').', 'Given pengemudi berada di layar \'Daftar Pengiriman\' When pengemudi memilih pengiriman \'TD-20231225-001\' yang akan dimulai And pengemudi menekan tombol \'Mulai Pelacakan\' Then sistem menampilkan status pelacakan \'Sedang Berlangsung\' di layar pengemudi And lokasi pengemudi mulai diperbarui di peta secara real-time.', 'Pengemudi berhasil memulai pelacakan. Status pengiriman berubah menjadi \'Sedang Berlangsung\'. Lokasi pengemudi mulai dikirimkan ke server dan dapat dilihat oleh pelanggan.', '2026-03-13 06:17:49'),
(56, 'LiveTracking', 'POSITIVE', 'BDD', 'Pelanggan Berhasil Melihat Pelacakan Langsung Pengiriman', 'Pelanggan sudah login ke aplikasi \'TrackMyDelivery\'. Koneksi internet stabil. Pengemudi sudah memulai pelacakan untuk ID pengiriman yang relevan.', 'ID Pengiriman: TD-20231225-001 (sedang aktif dilacak oleh pengemudi).', 'Given pelanggan berada di layar utama aplikasi \'TrackMyDelivery\' When pelanggan memasukkan ID pengiriman \'TD-20231225-001\' yang valid ke kolom pencarian And pelanggan menekan tombol \'Cari/Lacak\' Then sistem menampilkan peta dengan lokasi pengemudi saat ini And lokasi pengemudi diperbarui secara real-time di peta.', 'Pelanggan berhasil melihat lokasi pengemudi secara langsung di peta. Penanda lokasi pengemudi bergerak sesuai dengan pergerakan pengemudi di lapangan.', '2026-03-13 06:17:49'),
(57, 'LiveTracking', 'POSITIVE', 'BDD', 'Pengemudi Berhasil Menghentikan Pelacakan Pengiriman', 'Pengemudi sedang dalam mode pelacakan aktif untuk pengiriman \'TD-20231225-001\'. GPS aktif di perangkat. Koneksi internet stabil.', 'ID Pengiriman: TD-20231225-001 (sedang aktif dilacak).', 'Given pengemudi berada di layar pelacakan aktif untuk pengiriman \'TD-20231225-001\' When pengemudi menekan tombol \'Selesai Pengiriman\' And pengemudi mengonfirmasi penghentian pelacakan melalui dialog konfirmasi Then sistem menampilkan status pengiriman \'Selesai\' And pelacakan lokasi berhenti serta tidak ada pembaruan lebih lanjut.', 'Pengemudi berhasil menghentikan pelacakan. Status pengiriman berubah menjadi \'Selesai\'. Lokasi pengemudi tidak lagi dikirim atau diperbarui ke server.', '2026-03-13 06:17:49'),
(58, 'LiveTracking', 'NEGATIVE', 'BDD', 'Pengemudi Gagal Memulai Pelacakan Tanpa GPS Aktif', 'Pengemudi sudah login ke aplikasi \'TrackMyDelivery\'. Koneksi internet stabil. GPS dinonaktifkan pada perangkat.', 'ID Pengiriman: TD-20231225-002.', 'Given pengemudi berada di layar \'Daftar Pengiriman\' When pengemudi memilih pengiriman \'TD-20231225-002\' yang akan dimulai And pengemudi menekan tombol \'Mulai Pelacakan\' Then sistem menampilkan pesan peringatan \'GPS Anda tidak aktif. Mohon aktifkan GPS untuk memulai pelacakan.\' And pelacakan tidak dimulai.', 'Aplikasi menampilkan pesan kesalahan yang jelas mengenai GPS yang tidak aktif. Pelacakan pengiriman tidak dimulai.', '2026-03-13 06:17:49'),
(59, 'LiveTracking', 'NEGATIVE', 'BDD', 'Pelanggan Gagal Melihat Pelacakan Dengan ID Pengiriman Tidak Valid', 'Pelanggan sudah login ke aplikasi \'TrackMyDelivery\'. Koneksi internet stabil.', 'ID Pengiriman: \'INVALID123\' (ID yang tidak ada atau salah format).', 'Given pelanggan berada di layar utama aplikasi \'TrackMyDelivery\' When pelanggan memasukkan ID pengiriman \'INVALID123\' yang tidak valid/tidak ada ke kolom pencarian And pelanggan menekan tombol \'Cari/Lacak\' Then sistem menampilkan pesan peringatan \'ID Pengiriman tidak ditemukan. Mohon periksa kembali ID Anda.\' And peta tidak menampilkan lokasi atau informasi pelacakan apapun.', 'Aplikasi menampilkan pesan kesalahan yang memberitahu bahwa ID pengiriman tidak ditemukan. Tidak ada informasi pelacakan yang ditampilkan di peta.', '2026-03-13 06:17:49'),
(60, 'LiveTracking', 'NEGATIVE', 'BDD', 'Pengemudi Gagal Memulai Pelacakan Tanpa Koneksi Internet', 'Pengemudi sudah login ke aplikasi \'TrackMyDelivery\'. GPS aktif di perangkat. Koneksi internet dinonaktifkan (misal: mode pesawat atau data seluler mati).', 'ID Pengiriman: TD-20231225-003.', 'Given pengemudi berada di layar \'Daftar Pengiriman\' When pengemudi memilih pengiriman \'TD-20231225-003\' yang akan dimulai And pengemudi menekan tombol \'Mulai Pelacakan\' Then sistem menampilkan pesan peringatan \'Tidak ada koneksi internet. Mohon periksa koneksi Anda.\' And pelacakan tidak dimulai.', 'Aplikasi menampilkan pesan kesalahan yang jelas mengenai tidak adanya koneksi internet. Pelacakan pengiriman tidak dimulai.', '2026-03-13 06:17:49'),
(61, 'LiveTracking', 'NEGATIVE', 'BDD', 'Pengemudi Gagal Memulai Pelacakan Saat Baterai Kritis', 'Pengemudi sudah login ke aplikasi \'TrackMyDelivery\'. GPS aktif di perangkat. Koneksi internet stabil. Level baterai perangkat di bawah 5%.', 'ID Pengiriman: TD-20231225-004.', 'Given pengemudi berada di layar \'Daftar Pengiriman\' When pengemudi memilih pengiriman \'TD-20231225-004\' yang akan dimulai And pengemudi menekan tombol \'Mulai Pelacakan\' Then sistem menampilkan pesan peringatan \'Level baterai Anda kritis. Pelacakan tidak dapat dimulai.\' And pelacakan tidak dimulai.', 'Aplikasi menampilkan pesan peringatan bahwa pelacakan tidak dapat dimulai karena level baterai kritis. Pelacakan pengiriman tidak dimulai.', '2026-03-13 06:17:49'),
(62, 'LiveTracking', 'POSITIVE', 'ACTION_EXPECTED', 'Menampilkan Lokasi Real-time Perangkat Terpilih', 'Pengguna sudah login, perangkat pelacak aktif dan mengirim data, memiliki setidaknya satu perangkat yang dipasangkan.', 'Device ID: \"TRACKER-001\", Lokasi: {Lat: -6.2, Lon: 106.8}, Kecepatan: 50 km/jam, Status: Bergerak.', '1. [Action] Buka aplikasi Live Tracking -> [Expected] Layar utama atau dashboard muncul. 2. [Action] Pilih menu \"Perangkat Saya\" -> [Expected] Daftar perangkat yang dipasangkan muncul. 3. [Action] Pilih perangkat \"TRACKER-001\" dari daftar -> [Expected] Layar pelacakan langsung untuk \"TRACKER-001\" muncul. 4. [Action] Amati peta dan detail lokasi -> [Expected] Lokasi perangkat \"TRACKER-001\" ditampilkan di peta, dengan informasi kecepatan, dan status (misalnya, bergerak/berhenti).', 'Lokasi real-time perangkat \"TRACKER-001\" berhasil ditampilkan di peta dengan akurat, bersama dengan detail status (kecepatan, bergerak/berhenti).', '2026-03-13 06:18:53'),
(63, 'LiveTracking', 'POSITIVE', 'ACTION_EXPECTED', 'Pembaruan Lokasi Otomatis pada Peta', 'Pengguna sedang melihat pelacakan langsung untuk perangkat aktif, perangkat bergerak, memiliki koneksi internet yang stabil.', 'Device ID: \"TRACKER-001\", Perubahan Lokasi: Setiap 5 detik, Lokasi awal: {Lat: -6.200, Lon: 106.800}, Lokasi setelah 5 detik: {Lat: -6.201, Lon: 106.801}.', '1. [Action] Lanjutkan dari langkah terakhir skenario \'Menampilkan Lokasi Real-time Perangkat Terpilih\' (layar pelacakan langsung untuk \"TRACKER-001\" terbuka) -> [Expected] Lokasi \"TRACKER-001\" ditampilkan di peta. 2. [Action] Simulasikan pergerakan perangkat \"TRACKER-001\" (misalnya, perangkat fisik bergerak atau data simulasi diterima) -> [Expected] Peta secara otomatis memuat ulang atau memindahkan penanda lokasi ke posisi baru setelah interval pembaruan (misalnya 5 detik). 3. [Action] Amati detail lokasi (koordinat, kecepatan) setelah pembaruan -> [Expected] Detail lokasi diperbarui sesuai dengan posisi dan status terbaru perangkat.', 'Peta berhasil memperbarui posisi perangkat secara otomatis dan akurat sesuai dengan data lokasi terbaru yang diterima dalam interval waktu yang ditentukan.', '2026-03-13 06:18:53'),
(64, 'LiveTracking', 'NEGATIVE', 'ACTION_EXPECTED', 'Penanganan Tanpa Koneksi Internet Saat Pelacakan Langsung', 'Pengguna sudah login, perangkat pelacak aktif, namun tidak ada koneksi internet pada perangkat Android.', 'Device ID: \"TRACKER-002\", Status Koneksi Internet: Mati.', '1. [Action] Matikan koneksi internet (Wi-Fi/Data Seluler) pada perangkat Android -> [Expected] Koneksi internet terputus. 2. [Action] Buka aplikasi Live Tracking -> [Expected] Aplikasi terbuka, mungkin menampilkan pesan peringatan koneksi. 3. [Action] Pilih menu \"Perangkat Saya\" -> [Expected] Daftar perangkat muncul (jika data cache). 4. [Action] Pilih perangkat \"TRACKER-002\" dari daftar -> [Expected] Aplikasi mencoba memuat layar pelacakan langsung. 5. [Action] Amati tampilan layar pelacakan -> [Expected] Pesan kesalahan \"Tidak ada koneksi internet\" atau \"Gagal memuat data lokasi\" ditampilkan, peta tidak memuat, atau menampilkan status offline.', 'Aplikasi menampilkan pesan kesalahan yang jelas dan informatif mengenai tidak adanya koneksi internet dan tidak dapat memuat data pelacakan langsung. Peta tetap kosong atau menampilkan status offline yang sesuai.', '2026-03-13 06:18:53'),
(65, 'LiveTracking', 'NEGATIVE', 'ACTION_EXPECTED', 'Penanganan Perangkat Offline Saat Dicoba Dilacak', 'Pengguna sudah login, perangkat \"TRACKER-003\" dipasangkan tetapi tidak aktif (offline/tidak mengirim data).', 'Device ID: \"TRACKER-003\", Status Perangkat: Offline.', '1. [Action] Buka aplikasi Live Tracking -> [Expected] Layar utama muncul. 2. [Action] Pilih menu \"Perangkat Saya\" -> [Expected] Daftar perangkat yang dipasangkan muncul. 3. [Action] Pilih perangkat \"TRACKER-003\" dari daftar -> [Expected] Aplikasi mencoba memuat layar pelacakan langsung. 4. [Action] Amati tampilan layar pelacakan -> [Expected] Pesan kesalahan \"Perangkat tidak aktif\" atau \"Tidak ada data lokasi tersedia\", peta mungkin menunjukkan lokasi terakhir yang diketahui atau ikon perangkat berwarna abu-abu/offline.', 'Aplikasi berhasil mengidentifikasi bahwa perangkat \"TRACKER-003\" offline dan menampilkan pesan yang sesuai tanpa mencoba terus-menerus memuat data yang tidak ada, atau menampilkan lokasi terakhir yang diketahui.', '2026-03-13 06:18:53'),
(66, 'LiveTracking', 'POSITIVE', 'ACTION_EXPECTED', 'Berhasil Membuat Geofence Lingkaran Baru', 'Pengguna sudah login dan memiliki setidaknya satu perangkat yang dipasangkan, memiliki izin lokasi yang diperlukan.', 'Nama Geofence: \"Zona Kantor\", Radius: 500 meter, Lokasi Pusat: {Lat: -6.210, Lon: 106.810}.', '1. [Action] Buka aplikasi Live Tracking -> [Expected] Layar utama muncul. 2. [Action] Pilih menu \"Geofence\" -> [Expected] Daftar geofence yang sudah ada atau layar untuk membuat geofence baru muncul. 3. [Action] Klik tombol \"Tambah Geofence\" atau ikon \"+\" -> [Expected] Formulir pembuatan geofence muncul. 4. [Action] Masukkan \"Zona Kantor\" di kolom Nama Geofence -> [Expected] Nama geofence terisi. 5. [Action] Pilih tipe \"Lingkaran\" -> [Expected] Opsi untuk menentukan pusat dan radius muncul. 6. [Action] Pindahkan peta untuk menentukan pusat geofence di {Lat: -6.210, Lon: 106.810} dan atur radius menjadi 500 meter -> [Expected] Lingkaran geofence muncul di peta dengan ukuran yang benar. 7. [Action] Klik tombol \"Simpan\" -> [Expected] Geofence \"Zona Kantor\" berhasil disimpan dan muncul dalam daftar geofence.', 'Geofence lingkaran bernama \"Zona Kantor\" berhasil dibuat, ditampilkan di peta dengan lokasi dan radius yang ditentukan, dan muncul dalam daftar geofence pengguna.', '2026-03-13 06:18:53'),
(67, 'LiveTracking', 'NEGATIVE', 'ACTION_EXPECTED', 'Validasi Input Nama Saat Membuat Geofence', 'Pengguna sudah login dan memiliki setidaknya satu perangkat yang dipasangkan.', 'Nama Geofence: Kosong, Radius: 500 meter, Lokasi Pusat: {Lat: -6.210, Lon: 106.810}.', '1. [Action] Buka aplikasi Live Tracking -> [Expected] Layar utama muncul. 2. [Action] Pilih menu \"Geofence\" -> [Expected] Daftar geofence yang sudah ada atau layar untuk membuat geofence baru muncul. 3. [Action] Klik tombol \"Tambah Geofence\" atau ikon \"+\" -> [Expected] Formulir pembuatan geofence muncul. 4. [Action] Biarkan kolom Nama Geofence kosong -> [Expected] Kolom tetap kosong. 5. [Action] Pilih tipe \"Lingkaran\" -> [Expected] Opsi untuk menentukan pusat dan radius muncul. 6. [Action] Pindahkan peta untuk menentukan pusat geofence di {Lat: -6.210, Lon: 106.810} dan atur radius menjadi 500 meter -> [Expected] Lingkaran geofence muncul di peta dengan ukuran yang benar. 7. [Action] Klik tombol \"Simpan\" -> [Expected] Aplikasi menampilkan pesan kesalahan validasi seperti \"Nama geofence tidak boleh kosong\" atau \"Harap masukkan nama geofence\", dan geofence tidak disimpan.', 'Aplikasi mencegah penyimpanan geofence tanpa nama dan menampilkan pesan kesalahan yang jelas kepada pengguna, menjaga integritas data.', '2026-03-13 06:18:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `test_cases`
--
ALTER TABLE `test_cases`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `test_cases`
--
ALTER TABLE `test_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
