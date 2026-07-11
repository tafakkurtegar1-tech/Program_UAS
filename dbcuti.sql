-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 14. Agustus 2020 jam 17:15
-- Versi Server: 5.1.41
-- Versi PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dbcuti`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `approvecuti`
--

CREATE TABLE IF NOT EXISTS `approvecuti` (
  `idapprovecuti` varchar(10) NOT NULL,
  `idpengajuancuti` varchar(10) NOT NULL,
  `tanggalapprove` date NOT NULL,
  `approveby` varchar(20) NOT NULL,
  PRIMARY KEY (`idapprovecuti`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `approvecuti`
--

INSERT INTO `approvecuti` (`idapprovecuti`, `idpengajuancuti`, `tanggalapprove`, `approveby`) VALUES
('AP001', 'PC001', '2020-02-01', 'wiem');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jeniscuti`
--

CREATE TABLE IF NOT EXISTS `jeniscuti` (
  `idcuti` varchar(5) NOT NULL,
  `jeniscuti` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `jeniscuti`
--

INSERT INTO `jeniscuti` (`idcuti`, `jeniscuti`) VALUES
('CT003', 'Tahunan'),
('CT002', 'Urusan Keluarga'),
('CT001', 'Sakit'),
('CT000', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE IF NOT EXISTS `karyawan` (
  `nik` varchar(12) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `divisi` varchar(10) NOT NULL,
  `level` varchar(20) NOT NULL,
  `sisacuti` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama`, `divisi`, `level`, `sisacuti`) VALUES
('901234', 'Barkah', 'Fso', 'Staff', 2),
('789012', 'Asep', 'IT', 'Staff', 2),
('678901', 'Silva', 'HRD', 'Manager', 1),
('567890', 'JohnJay', 'FSO', 'Staff', 5),
('456789', 'Ismail', 'IT', 'Staff', 10),
('234567', 'Ojes', 'FSO', 'Staff', 4),
('123456', 'Efendi', 'IT', 'Staff', 4),
('345678', 'Kubil Setiawan', 'IT', 'Staff', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuancuti`
--

CREATE TABLE IF NOT EXISTS `pengajuancuti` (
  `idpengajuancuti` varchar(10) NOT NULL,
  `nik` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `divisi` varchar(30) NOT NULL,
  `sisacuti` int(5) NOT NULL,
  `idcuti` varchar(10) NOT NULL,
  `tanggalpengajuan` date NOT NULL,
  `tanggalmulai` date NOT NULL,
  `lamacuti` int(11) NOT NULL,
  `alasancuti` varchar(30) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`idpengajuancuti`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pengajuancuti`
--

INSERT INTO `pengajuancuti` (`idpengajuancuti`, `nik`, `nama`, `divisi`, `sisacuti`, `idcuti`, `tanggalpengajuan`, `tanggalmulai`, `lamacuti`, `alasancuti`, `status`) VALUES
('PC002', '234567', 'Ojes', 'Staff', 8, 'CT002', '2020-02-10', '2020-08-12', 4, 'Sakit', 'Proses'),
('PC003', '345678', 'Taufik', 'IT', 7, 'CT003', '2020-02-12', '2020-08-14', 5, 'Sakit', 'Proses'),
('PC004', '456789', 'Ismail', 'IT', 10, 'CT004', '2020-02-10', '2020-08-12', 2, 'Cuti Tahunan', 'Disetujui'),
('PC005', '567890', 'Jhon Jey', 'Staff', 11, 'CT005', '2020-08-17', '2020-08-18', 1, 'Sakit', 'Disetujui'),
('PC009', '789012', 'Silva', 'Manager', 9, 'CT002', '2020-08-03', '2020-08-07', 3, 'Urusan Keluarga', 'Proses'),
('PC006', '678901', 'Asep', 'Staff', 10, 'CT004', '2020-08-04', '2020-08-07', 2, 'Berobat keluar negeri', 'Proses'),
('PC008', '890123', 'Amelia Silva', 'Direktur', 11, 'CT004', '2020-08-03', '2020-08-05', 1, 'Cuti Tahunan', 'Diterima'),
('PC010', '890123', 'Amelia Silva', 'Direktur', 10, 'CT003', '2020-08-05', '2020-08-07', 2, 'Cuti Tahunan', 'proses'),
('PC011', '789012', 'Silva', 'Manager', 10, 'CT002', '2020-06-01', '2020-06-04', 2, 'Urusan Keluarga', 'DITERIMA'),
('PC012', '890123', 'Amelia Silva', 'Direktur', 9, 'CT002', '2020-08-05', '2020-08-10', 3, 'Urusan Keluarga', 'Proses');

-- --------------------------------------------------------

--
-- Struktur dari tabel `userlogin`
--

CREATE TABLE IF NOT EXISTS `userlogin` (
  `username` varchar(10) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `userlogin`
--

INSERT INTO `userlogin` (`username`, `password`) VALUES
('901234', '123456'),
('admin', 'admin'),
('234567', '123456'),
('345678', '123456'),
('456789', '123456'),
('567890', '123456'),
('678901', '123456'),
('789012', '123456'),
('890123', '123456'),
('123456', '123456'),
('987654', '123456');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
