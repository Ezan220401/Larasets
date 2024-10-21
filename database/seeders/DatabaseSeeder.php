<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'category_id' => 1,
            'category_name' => 'Ruangan',
            'category_desc' =>'Tempat berupa kelas atau aula yang dapat dipinjam untuk kegiatan kampus atau ormawa.', 
            'code' => 170.00, //tidak diketahui
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        Category::create([
            'category_id' => 2,
            'category_name' => 'Alat Pendingin',
            'category_desc' =>'Mesin Pendingin berupa Air Conditioner ataupun Kipas Angin yang dapat dipinjam secara terbatas.',
            'code' => 170.04,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        Category::create([
            'category_id' => 3,
            'category_name' => 'Barang Elektronik',
            'category_desc' =>'Barang yang dapat dipinjam untuk kegiatan kampus atau ormawa.',
            'code' => 170.05,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        Category::create([
            'category_id' => 4,
            'category_name' => 'Alat Kelas',
            'category_desc' =>'Barang yang sudah ada didalam kelas dan dapat dipinjam untuk kegiatan kampus atau ormawa.',
            'code' => 170.06,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        Category::create([
            'category_id' => 5,
            'category_name' => 'Alat Partisi dan Instalasi',
            'category_desc' =>'Peralatan pertukanagan yang dapat dipinjam untuk kegiatan kampus atau ormawa.',
            'code' => 170.08,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        Category::create([
            'category_id' => 6,
            'category_name' => 'Alat RTK',
            'category_desc' =>'Peralatan kantor atau kelas yang dapat dipinjam untuk kegiatan kampus atau ormawa secara terbatas.',
            'code' => 170.07,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        Category::create([
            'category_id' => 7,
            'category_name' => 'Kendaraan',
            'category_desc' =>'Kendaraan bermotor ataupun tak bermotor yang dapat dipakai untuk mendukungng kegiatan.',
            'code' => 170.03,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Kategori Peminjaman
        LoanCategory::create([
            'category_name' => 'Peminjaman Ruangan',
            'category_desc' =>'Peminjaman ruangan kelas atau aula sekaligus perlatan dan barang yang ada didalam ruangan yang dimaksud untuk mendukung kegiatan kampus atau ormawa',
            'approvals' => 'Akademik, Kemahasiswaan, Koordinator Aset',
            'for_one_position' => 'Koordinator Aset',
            'for_one_name' => 'Hena Sulaeman, S.T., M.Kom.'
        ]);
        LoanCategory::create([
            'category_name' => 'Peminjaman Kendaraan',
            'category_desc' =>'Peminjaman motor atau mobil untuk mendukung kegiatan kampus atau ormawa',
            'approvals' => 'Koordinator Aset, Wakil Rektor',
            'for_one_position' => 'Jabatan Bidang Pengembangan SDM dan Aset',
            'for_one_name' => 'Hena Sulaeman, S.T., M.Kom.'
        ]);
        LoanCategory::create([
            'category_name' => 'Peminjaman Barang',
            'category_desc' =>'Peminjaman barang seperti komputer, proyektor dan barang lain yang tercatat dalam sebuah ruangan untuk dipinjam keluar dari ruangan guna mendukung kegiatan kampus atau ormawa',
            'approvals' => 'Kemahasiswaan, Koordinator Aset, Wakil Rektor',
            'for_one_position' => 'Jabatan Bidang Pengembangan SDM, Keuangan dan Aset',
            'for_one_name' => 'Iswan Bugis, S.M'
        ]);
        LoanCategory::create([
            'category_name' => 'Peminjaman Alat',
            'category_desc' =>'Peminjaman alat seperti alat partisi, kantor dan instalasi yang tercatat dalam sebuah ruangan untuk dipinjam keluar dari ruangan guna mendukung kegiatan kampus atau ormawa',
            'approvals' => 'Kemahasiswaan, Koordinator Aset, Wakil Rektor',
            'for_one_position' => 'Jabatan Bidang Pengembangan SDM, Keuangan dan Aset',
            'for_one_name' => 'Iswan Bugis, S.M'
        ]);
        LoanCategory::create([
            'category_name' => 'Peminjaman Laboratorium',
            'category_desc' =>'Peminjaman Ruangan sekaligus perlatan dan barang yang ada didalam ruangan yang dimaksud untuk mendukung kegiatan kampus atau ormawa, namun dengan persetujuan Kepala Lab yang bersangkutan',
            'approvals' => 'Akademik, Kemahasiswaan, Kepala Lab, Wakil Rektor',
            'for_one_position' => 'Kepala Laboratorium',
            'for_one_name' => ''
        ]);

        // Peminjaman
        Loan::create([
            'loan_name' => 'Peminjaman Alat',
            'applicant_name' => 'Sera',
            'applicant_position' => 'Mahasiswa biasa',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000110',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Kamera DSLR',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-08-01 21:34:28',
            'loan_length' => '2024-08-01 23:55:28',
            'loan_note_status' => 'Disetujui Penuh',
            'created_by' => 7,
            'created_at' => '2024-07-01 23:55:28',
            'is_wr_approve' => 'Iswan Bugis',
            'is_coordinator_approve' => 'Hena Sulaeman',
            'is_student_approve' => 'Dilan',
            'is_full_approve' => true,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Alat',
            'applicant_name' => 'Sera',
            'applicant_position' => 'Mahasiswa biasa',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000110',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Kamera DSLR',
            'loan_asset_quantity' => 5,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-08-16 21:34:28',
            'loan_length' => '2024-08-16 23:55:28',
            'loan_note_status' => 'Disetujui Penuh',
            'created_by' => 7,
            'created_at' => '2024-09-01 23:55:28',
            'is_wr_approve' => 'Iswan Bugis',
            'is_coordinator_approve' => 'Hena Sulaeman',
            'is_student_approve' => 'Dilan',
            'is_full_approve' => true,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Alat',
            'applicant_name' => 'Sera',
            'applicant_position' => 'Mahasiswa biasa',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000110',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Kamera DSLR',
            'loan_asset_quantity' => 3,
            'created_at' => '2024-08-01 23:55:28',
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-08-09 21:34:28',
            'loan_length' => '2024-08-10 23:55:28',
            'loan_note_status' => 'Disetujui Penuh',
            'created_by' => 7,
            'is_wr_approve' => 'Iswan Bugis',
            'is_coordinator_approve' => 'Hena Sulaeman',
            'is_student_approve' => 'Dilan',
            'is_full_approve' => true,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Alat',
            'applicant_name' => 'Sera',
            'applicant_position' => 'Mahasiswa biasa',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000110',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Kamera DSLR',
            'loan_asset_quantity' => 2,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-08-30 21:34:28',
            'loan_length' => '2024-08-30 23:55:28',
            'loan_note_status' => 'Disetujui Penuh',
            'created_by' => 7,
            'created_at' => '2024-03-18 23:55:28',
            'is_wr_approve' => 'Iswan Bugis',
            'is_coordinator_approve' => 'Hena Sulaeman',
            'is_student_approve' => 'Dilan',
            'is_full_approve' => true,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Alat',
            'applicant_name' => 'Ezra',
            'applicant_position' => 'Mahasiswa biasa',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Kelas',
            'loan_asset_name' => 'Printer 3D',
            'loan_asset_quantity' => 3,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-08-09 11:34:28',
            'loan_length' => '2024-08-09 17:55:28',
            'loan_note_status' => 'Disetujui Penuh',
            'created_by' => 7,
            'created_at' => '2024-03-31 23:55:28',
            'is_wr_approve' => 'Iswan Bugis',
            'is_coordinator_approve' => 'Hena Sulaeman',
            'is_student_approve' => 'Dilan',
            'is_full_approve' => true,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Alat',
            'applicant_name' => 'Ezra',
            'applicant_position' => 'Mahasiswa biasa',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Kelas',
            'loan_asset_name' => 'Printer HP LaserJet Pro',
            'loan_asset_quantity' => 3,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-08-09 11:34:28',
            'loan_length' => '2024-08-09 17:55:28',
            'loan_note_status' => 'Disetujui Penuh',
            'created_by' => 7,
            'created_at' => '2024-03-11 23:55:28',
            'is_wr_approve' => 'Iswan Bugis',
            'is_coordinator_approve' => 'Hena Sulaeman',
            'is_student_approve' => 'Dilan',
            'is_full_approve' => true,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Alat',
            'applicant_name' => 'Eka',
            'applicant_position' => 'Mahasiswa biasa',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Kamera',
            'loan_asset_quantity' => 3,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-08-09 11:34:28',
            'loan_length' => '2024-08-09 17:55:28',
            'loan_note_status' => 'Disetujui Penuh',
            'created_by' => 7,
            'created_at' => '2024-02-01 23:55:28',
            'is_wr_approve' => 'Iswan Bugis',
            'is_coordinator_approve' => 'Hena Sulaeman',
            'is_student_approve' => 'Dilan',
            'is_full_approve' => true,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Barang',
            'applicant_name' => 'Arze',
            'applicant_position' => 'Sekertaris',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Tenda',
            'loan_asset_quantity' => 3,
            'created_at' => '2024-03-22 23:55:28',
            'is_full_approve' => true,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' =>  '2024-08-09 00:34:28',
            'loan_length' => '2024-08-02 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 7,
        ]);
        
        Loan::create([
            'loan_name' => 'Peminjaman Barang',
            'applicant_name' => 'Reza',
            'applicant_position' => 'Sekertaris',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000101',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Tas P3K',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' =>  '2024-08-09 15:34:28',
            'loan_length' =>  '2024-08-09 17:34:28',
            'loan_note_status' => 'Menggunakan',
            'is_full_approve' => true,
            'created_at' => '2024-03-22 23:55:28',
            'is_using' => true,
            'using_id' => 110,
            'created_by' => 'Mahasiswa',
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Barang',
            'applicant_name' => 'Arze',
            'applicant_position' => 'Sekertaris',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Tas P3K',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' =>  '2024-08-09 08:34:28',
            'loan_length' =>  '2024-08-09 11:34:28',
            'loan_note_status' => 'Dikembalikan',
            'is_full_approve' => true,
            'is_using' => true,
            'using_id' => 110,
            'created_at' => '2024-03-22 23:55:28',
            'is_returned' => true,
            'return_id' => 112,
            'created_by' => 'Mahasiswa',
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Alat',
            'applicant_name' => 'Arze',
            'applicant_position' => 'Sekertaris',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Tenda',
            'loan_asset_quantity' => 3,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-04-20 00:34:28',
            'created_at' => '2024-03-12 23:55:28',
            'loan_length' => '2024-04-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 7,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Kendaraan',
            'applicant_name' => 'Arze',
            'applicant_position' => 'Sekertaris',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Mobil SUV',
            'loan_asset_quantity' => 3,
            'created_at' => '2024-05-02 23:55:28',
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-04-20 00:34:28',
            'loan_length' => '2024-04-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 7,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Kendaraan',
            'applicant_name' => 'Hamda',
            'applicant_position' => 'Sekertaris',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Motor',
            'loan_asset_quantity' => 3,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-04-22 00:34:28',
            'loan_length' => '2024-04-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 7,
            'created_at' => '2024-03-30 20:55:28',
        ]); Loan::create([
            'loan_name' => 'Peminjaman Kendaraan',
            'applicant_name' => 'Sera',
            'applicant_position' => 'Sekertaris',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Hutan',
            'loan_asset_name' => 'Mobil SUV',
            'loan_asset_quantity' => 3,
            'loan_desc' => 'Data Dummy | Pemotretan',
            'loan_date' => '2024-04-22 00:34:28',
            'loan_length' => '2024-04-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 7,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Laboratorium',
            'applicant_name' => 'Suna',
            'applicant_position' => 'Karyawan',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Lab Komputer',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Rapat. Untuk 12 orang',
            'loan_date' => '2024-06-20 00:34:28',
            'loan_length' => '2024-06-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
            'created_at' => '2024-05-22 23:55:28',
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Laboratorium',
            'applicant_name' => 'Suna',
            'applicant_position' => 'Karyawan',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Lab Ergonomi',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Rapat. Untuk 12 orang',
            'loan_date' => '2024-06-20 00:34:28',
            'loan_length' => '2024-06-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
            'created_at' => '2024-09-06 23:55:28',
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Laboratorium',
            'applicant_name' => 'Suna',
            'applicant_position' => 'Karyawan',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Lab Komputer',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Rapat. Untuk 12 orang',
            'loan_date' => '2024-05-23 00:34:28',
            'loan_length' => '2024-05-23 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
            'created_at' => '2024-09-10 23:55:28',
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Laboratorium',
            'applicant_name' => 'Suna',
            'applicant_position' => 'Karyawan',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Lab Komputer',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Rapat. Untuk 12 orang',
            'loan_date' => '2024-04-20 00:34:28',
            'loan_length' => '2024-04-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
            'created_at' => '2024-06-22 23:55:28',
        ]);
        
        Loan::create([
            'loan_name' => 'Peminjaman Laboratorium',
            'applicant_name' => 'Suna',
            'applicant_position' => 'Karyawan',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Lab Komputer',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Rapat. Untuk 12 orang',
            'loan_date' => '2024-04-30 00:34:28',
            'loan_length' => '2024-04-30 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
            'created_at' => '2024-06-22 23:55:28',
        ]);
        
        Loan::create([
            'loan_name' => 'Peminjaman Laboratorium',
            'applicant_name' => 'Suna',
            'applicant_position' => 'Karyawan',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Lab Ergonomi',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Rapat. Untuk 12 orang',
            'loan_date' => '2024-04-30 00:34:28',
            'loan_length' => '2024-04-30 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
            'created_at' => '2024-06-22 23:55:28',
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Ruangan',
            'applicant_name' => 'Hilam',
            'applicant_position' => 'Dosen',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Kelas A113',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Kelas online. Untuk 2 orang',
            'loan_date' => '2024-04-20 00:34:28',
            'loan_length' => '2024-04-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
            'created_at' => '2024-06-28 23:55:28',
        ]);
        
        Loan::create([
            'loan_name' => 'Peminjaman Ruangan',
            'applicant_name' => 'Hilam',
            'applicant_position' => 'Dosen',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Kelas A113',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Kelas online. Untuk 2 orang',
            'loan_date' => '2024-03-21 00:34:28',
            'loan_length' => '2024-04-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Ruangan',
            'applicant_name' => 'Hilam',
            'applicant_position' => 'Dosen',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Kelas A111',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Kelas online. Untuk 2 orang',
            'loan_date' => '2024-04-22 00:34:28',
            'loan_length' => '2024-04-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
            'created_at' => '2024-09-01 23:55:28',
        ]);
        Loan::create([
            'loan_name' => 'Peminjaman Ruangan',
            'applicant_name' => 'Hilam',
            'applicant_position' => 'Dosen',
            'applicant_phone' => '0896883000',
            'applicant_number_id' => '20550000111',
            'loan_position' => 'Ditempat',
            'loan_asset_name' => 'Kelas A112',
            'loan_asset_quantity' => 1,
            'loan_desc' => 'Data Dummy | Kelas online. Untuk 2 orang',
            'loan_date' => '2024-04-21 00:34:28',
            'loan_length' => '2024-04-22 00:55:28',
            'loan_note_status' => 'Menunggu Persetujuan',
            'created_by' => 8,
        ]);

        //Koordinator Aset
        UserGroup::create([
            'group_id' => 1,
            'group_name' => 'Koordinator Aset',
            'group_desc' => 'Jabatan yang memastikan semua aset digital kami disimpan secara strategis, memenuhi pedoman merek, dan tersedia untuk distribusi digital melalui berbagai saluran.',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        User::create([
            'user_email' => 'coordinator@gmail.com',
            'user_password' => bcrypt('root'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '16162001',
            'user_name' => 'Hena Sulaeman, S.T., M.Kom.',
            'user_phone' =>'62896887272',
            'group_id' => 1,
        ]);

        //Akademik
        UserGroup::create([
            'group_id' => 2,
            'group_name' => 'Akademik',
            'group_desc' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eaque, quo!',
            'created_by' => 1,
        ]);
        User::create([
            'user_email' => 'academic@gmail.com',
            'user_password' => bcrypt('adminacademic'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '161620000131',
            'user_name' => 'Admin Akademik',
            'user_phone' =>'62896887272',
            'group_id' => 2,
        ]);

        //Wakil Rektor
        UserGroup::create([
            'group_id' => 3,
            'group_name' => 'Wakil Rektor',
            'group_desc' => 'Jabatan yang bermitra dengan entitas eksternal untuk acara kampus dan memberikan pendidikan, komunikasi, dan manajemen perubahan untuk proses perencanaan sumber daya perusahaan',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        User::create([
            'user_email' => 'wr2@gmail.com',
            'user_password' => bcrypt('adminwk'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '161620000112',
            'user_name' => 'Diana',
            'user_phone' =>'62896887272',
            'group_id' => 3,
        ]); 
        User::create([
            'user_email' => 'wr1@gmail.com',
            'user_password' => bcrypt('adminpk'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '161620000113',
            'user_name' => 'Bili',
            'user_phone' =>'62396887272',
            'group_id' => 3,
        ]);

        // Kemahasiswaan
        UserGroup::create([
            'group_id' => 4,
            'group_name' => 'Kemahasiswaan',
            'group_desc' => 'Jabatan yang melayani pembinaan minat, bakat, dan penalaran kemahasiswaan. Pelaksanaan administrasi kegiatan kemahasiswaan. Pelaksanaan layanan kesejahteraan mahasiswa.',
            'created_by' => 1,
        ]);
        User::create([
            'user_email' => 'studentcoordinator@gmail.com',
            'user_password' => bcrypt('adminstudent'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '161620000114',
            'user_name' => 'Ahsani Takwim, S.Kom., M.Kom.',
            'user_phone' =>'62196887272',
            'group_id' => 4,
        ]);

        //Dosen
        UserGroup::create([
            'group_id' => 5,
            'group_name' => 'Dosen',
            'group_desc' => 'Pengajar kampus',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        User::create([
            'user_email' => 'dosen@gmail.com',
            'user_password' => bcrypt('87654321'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '16162000115',
            'user_name' => 'Hilam',
            'user_phone' =>'62896887272',
            'group_id' => 5,
        ]);

        //Karyawan
        UserGroup::create([
            'group_id' => 6,
            'group_name' => 'Karyawan',
            'group_desc' => 'Pekerja Kampus',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        User::create([
            'user_email' => 'karyawan@gmail.com',
            'user_password' => bcrypt('adminkaryawan'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '16162000116',
            'user_name' => 'Suna',
            'user_phone' =>'62896887272',
            'group_id' => 6,
        ]);

        // Mahasiswa
        UserGroup::create([
            'group_id' => 7,
            'group_name' => 'Mahasiswa',
            'group_desc' => 'Pelajar di Kampus',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        User::create([
            'user_email' => 'ezrapakpahan123@gmail.com',
            'user_password' => bcrypt('12345678'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '20552011083',
            'user_name' => 'Ezra Jon Freddy Pakpahan',
            'user_phone' =>' 629688355159',
            'group_id' => 7,
        ]);
        User::create([
            'user_email' => 'pengguna@gmail.com',
            'user_password' => bcrypt('12345678'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '20552011083',
            'user_name' => 'Puji Cosi',
            'user_phone' =>' 629688355159',
            'group_id' => 7,
        ]);

        //PJM
        UserGroup::create([
            'group_id' => 8,
            'group_name' => 'PJM',
            'group_desc' => 'Pusat Jaminan Mutu yang menyusun kebijakan mutu universitas, menyusun manual mutu universitas berdasarkan laporan yang diterima dari berbagai aspek dan jabatan',
            'created_by' => 1,
        ]);
        User::create([
            'user_email' => 'PJM@gmail.com',
            'user_password' => bcrypt('PJM'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '161620000117',
            'user_name' => 'Kalang',
            'user_phone' =>'6296887272',
            'group_id' => 8,
        ]);  
        
        //Pusdatin
        UserGroup::create([
            'group_id' => 99,
            'group_name' => 'Pusdatin',
            'group_desc' => 'Penanggungjawab sistem dan informasi',
            'created_by' => 1,
        ]);
        User::create([
            'user_email' => 'pusdatin@gmail.com',
            'user_password' => bcrypt('pusdatin'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '161620000118',
            'user_name' => 'Gilang',
            'user_phone' =>'6296887272',
            'group_id' => 9,
        ]);  
        //PJM
        UserGroup::create([
            'group_id' => 9,
            'group_name' => 'Security',
            'group_desc' => 'Penjaga keamanan kampus',
            'created_by' => 1,
        ]);
        User::create([
            'user_email' => 'security@gmail.com',
            'user_password' => bcrypt('security'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '161620000119',
            'user_name' => 'Alam',
            'user_phone' =>'6296887272',
            'group_id' => 9,
        ]);  
        // Kepala Lab
        UserGroup::create([
            'group_id' => 10,
            'group_name' => 'Kepala Laboratorium Komputer',
            'group_desc' => 'Jabatan yang bertanggung jawab dalam memastikan, menjaga dan melaporkan kelayakan operasional dari ruangan serta peralatan dan barang yang terdaftar dalam ruangan laboratorium.',
            'created_by' => 1,
        ]);
        User::create([
            'user_email' => 'labcom@gmail.com',
            'user_password' => bcrypt('adminlab'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '161620000121',
            'user_name' => 'Admin Lab Komputer',
            'user_phone' =>'6296887272',
            'group_id' => 10,
        ]);
        UserGroup::create([
            'group_id' => 11,
            'group_name' => 'Kepala Laboratorium Ergonomi',
            'group_desc' => 'Jabatan yang bertanggung jawab dalam memastikan, menjaga dan melaporkan kelayakan operasional dari ruangan serta peralatan dan barang yang terdaftar dalam ruangan laboratorium.',
            'created_by' => 1,
        ]);
        User::create([
            'user_email' => 'labergo@gmail.com',
            'user_password' => bcrypt('adminlab'),
            'user_photo' => 'bsabfuii3qwh8w2ij2kmfkqwm.png',
            'user_number_id' => '161620000122',
            'user_name' => 'Admin Lab Ergonomi',
            'user_phone' =>'6296887272',
            'group_id' => 11,
        ]);

        // Aset
        Asset::create([
            'asset_name' => 'Tas P3K',
            'category_id' => 3,
            'asset_type' => 'Ransel',
            'asset_position' => 'Gedung B, Ruang Kemahasiswaan di lantai 2',
            'asset_desc' => 'Memiliki Antibiotik, paracetamol, obat luka, dan buku panduan terkait pertolongan pertama',
            'asset_date_of_entry' => '2000-04-25 14:30:00',
            'asset_quantity' => 2,
            'maintenance_desc' => 'Setiap minggu, periksa isi antibiotik, tanggal kadaluarsa obat-obatan serta isinya, bila hampir habis, segera isi ulang.',
            'receipt_number' => 3123,
            'created_by' => 4,
            'updated_by' => 3,
            'asset_image' => null,
            'asset_code' => '170.05.01',
            'asset_price' => '15000000',
        ]);
        Asset::create([
            'asset_name' => 'Kamera',
            'category_id' => 3,
            'asset_type' => 'DSLR',
            'maintenance_desc' => 'Setiap bulan periksa memori dan kondisi',
            'asset_position' => 'Gedung B, Ruang Kemahasiswaan di lantai 2',
            'asset_desc' => 'Canon EOS 90D dengan lensa 18-135mm, 32.5 MP',
            'asset_date_of_entry' => '2024-04-25 14:30:00',
            'asset_quantity' => 10,
            'receipt_number' => 3123,
            'created_by' => 4,
            'updated_by' => 3,
            'asset_image' => null,
            'asset_code' => '170.05.01',
            'asset_price' => '15000000',
        ]);
        Asset::create([
            'asset_name' => 'Kamera',
            'category_id' => 3,
            'maintenance_desc' => 'Setiap bulan periksa memori dan kondisi',
            'asset_type' => 'Handy',
            'asset_position' => 'Gedung B, Ruang Kemahasiswaan di lantai 2',
            'asset_desc' => 'Nikon EOS 90D dengan lensa 18-135mm, 32.5 MP',
            'asset_date_of_entry' => '2024-04-25 14:30:00',
            'asset_quantity' => 10,
            'receipt_number' => 3123,
            'created_by' => 4,
            'updated_by' => 3,
            'asset_image' => null,
            'asset_code' => '170.05.02',
            'asset_price' => '15000000',
        ]);
        Asset::create([
            'asset_name' => 'Laptop',
            'category_id' => 6,
            'maintenance_desc' => 'Setiap bulan periksa memori dan kondisi',
            'asset_type' => 'Dell XPS 13',
            'asset_position' => 'Gedung A, Ruang Akademik',
            'asset_desc' => 'Dell XPS 13, RAM 16GB, SSD 512GB, Intel i7',
            'asset_date_of_entry' => '2024-06-01 09:00:00',
            'asset_quantity' => 15,
            'receipt_number' => 3124,
            'created_by' => 5,
            'updated_by' => 4,
            'asset_image' => null,
            'asset_code' => '170.07.01',
            'asset_price' => '20000000',
        ]);
        Asset::create([
            'asset_name' => 'Komputer',
            'category_id' => 6,
            'maintenance_desc' => 'Setiap bulan periksa memori dan kondisi',
            'asset_type' => 'Dell XPS 13',
            'asset_position' => 'Gedung A, Ruang IT',
            'asset_desc' => 'Dell XPS 13, RAM 16GB, SSD 512GB, Intel i7',
            'asset_date_of_entry' => '2024-06-01 09:00:00',
            'asset_quantity' => 15,
            'receipt_number' => 3124,
            'created_by' => 5,
            'updated_by' => 4,
            'asset_image' => null,
            'asset_code' => '170.07.02',
            'asset_price' => '20000000',
        ]);
        Asset::create([
            'asset_name' => 'Lab Komputer',
            "category_id" => 1,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'asset_type' => 'Ruangan Jaringan',
            'asset_code' => '170.00.01',
            'asset_position' => 'Gedung A, Ruangan di Lantai 1',
            'asset_desc' => 'Lab dengan 20 Komputer dan 3 Pen Display',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'receipt_number' => 2123,
            'asset_price' => '25000000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Aula A',
            "category_id" => 1,
            'asset_type' => 'Ruang Tertutup Serbaguna',
            'asset_code' => '170.00.02',
            'asset_position' => 'Gedung A, Ruangan di Lantai 1',
            'asset_desc' => 'Lapangan tertutup dengan ukuran 81 meter persegi',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'receipt_number' => 2123,
            'asset_price' => '2500000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Aula B',
            "category_id" => 1,
            'asset_type' => 'Ruang Tertutup Serbaguna',
            'asset_code' => '170.00.03',
            'asset_position' => 'Gedung A, Ruangan di Lantai 1',
            'asset_desc' => 'Lapangan tertutup dengan ukuran 81 meter persegi',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'receipt_number' => 2123,
            'asset_price' => '2000000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Lab Ergonomi',
            "category_id" => 1,
            'asset_type' => 'Ruangan Industri',
            'asset_code' => '170.00.04',
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'asset_position' => 'Gedung A, Ruangan di Lantai 1',
            'asset_desc' => 'Tempat simulasi dan fasilitas yang mendukung pendidikan, penelitian, dan pengabdian masyarakat di bidang ergonom',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'receipt_number' => 2123,
            'asset_price' => '25000000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Kelas A110',
            "category_id" => 1,
            'asset_type' => 'Kelas',
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'asset_code' => '170.00.05',
            'asset_position' => 'Gedung A, Ruangan di Lantai 1',
            'asset_desc' => 'Kelas untuk belajar dan melakukan kegiatan ormawa dalam skala kecil',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'receipt_number' => 2123,
            'asset_price' => '25000000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Kelas A210',
            "category_id" => 1,
            'asset_type' => 'Kelas',
            'asset_code' => '170.00.06',
            'asset_position' => 'Gedung A, Ruangan di Lantai 2',
            'asset_desc' => 'Kelas untuk belajar dan melakukan kegiatan ormawa dalam skala kecil',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'receipt_number' => 2123,
            'asset_price' => '25000000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Kelas B110',
            "category_id" => 1,
            'asset_type' => 'Kelas',
            'asset_code' => '170.00.07',
            'asset_position' => 'Gedung B, Ruangan di Lantai 1',
            'asset_desc' => 'Kelas untuk belajar dan melakukan kegiatan ormawa dalam skala kecil',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'receipt_number' => 2123,
            'asset_price' => '25000000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Kelas B120',
            "category_id" => 1,
            'asset_type' => 'Kelas',
            'asset_code' => '170.00.08',
            'asset_position' => 'Gedung A, Ruangan di Lantai 2',
            'asset_desc' => 'Kelas untuk belajar dan melakukan kegiatan ormawa dalam skala kecil',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'receipt_number' => 2123,
            'asset_price' => '25000000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Garasi',
            "category_id" => 1,
            'asset_type' => 'penyimpanan kendaraan',
            'asset_code' => '170.00.09',
            'asset_position' => 'Gedung A, Ruangan di Lantai 1',
            'asset_desc' => 'Lab dengan 20 Komputer dan 3 Pen Display',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'receipt_number' => 2123,
            'asset_price' => '25000000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Gudang',
            "category_id" => 1,
            'asset_type' => 'Penyimpanan peralatan tak terpakai',
            'asset_code' => '170.00.10',
            'asset_position' => 'Gedung A, Ruangan di Lantai 1',
            'asset_desc' => 'Lab dengan 20 Komputer dan 3 Pen Display',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 1,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'receipt_number' => 2123,
            'asset_price' => '25000000',
            'created_by' => 2,
            'updated_by' => 3,
            'asset_image' => null,
        ]);
        Asset::create([
            'asset_name' => 'Proyektor',
            'category_id' => 4,
            'asset_type' => 'Epson EB-X41',
            'asset_position' => 'Gedung A, Lantai 1 di Kelas A111',
            'asset_desc' => 'Proyektor Epson EB-X41, resolusi XGA, 3600 lumens',
            'asset_date_of_entry' => '2024-05-15 11:00:00',
            'asset_quantity' => 7,
            'receipt_number' => 4123,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'created_by' => 6,
            'updated_by' => 5,
            'asset_image' => null,
            'asset_code' => '170.06.01',
            'asset_price' => '8000000',
        ]);
        Asset::create([
            'asset_name' => 'Proyektor',
            'category_id' => 4,
            'asset_type' => 'Epson EB-X41',
            'asset_position' => 'Gedung B, Lantai 1 di Kelas B111',
            'asset_desc' => 'Proyektor Epson EB-X41, resolusi XGA, 3600 lumens',
            'asset_date_of_entry' => '2024-05-15 11:00:00',
            'asset_quantity' => 7,
            'receipt_number' => 4123,
            'maintenance_desc' => 'Setiap hari periksa kebersihan dan kondisi',
            'created_by' => 6,
            'updated_by' => 5,
            'asset_image' => null,
            'asset_code' => '170.06.02',
            'asset_price' => '8000000',
        ]);
        Asset::create([
            'asset_name' => 'Printer',
            'category_id' => 6,
            'asset_type' => 'HP LaserJet Pro',
            'asset_position' => 'Gedung A, Ruang Administrasi',
            'asset_desc' => 'HP LaserJet Pro MFP M428fdw, cetak, scan, copy, fax',
            'asset_date_of_entry' => '2024-06-10 13:00:00',
            'asset_quantity' => 8,
            'receipt_number' => 4124,
            'maintenance_desc' => 'Setiap hari periksa kebersihan, tinta, kertas dan kondisi',
            'created_by' => 7,
            'updated_by' => 6,
            'asset_image' => null,
            'asset_code' => '170.06.01',
            'asset_price' => '5000000',
        ]);
        Asset::create([
            'asset_name' => 'Mobil',
            'category_id' => 7,
            'asset_type' => 'SUV',
            'asset_position' => 'Gedung B, Ruangan Garasi A',
            'asset_desc' => 'Suzuki SUV, muat 4 sampai 8 orang penumpang, kapasitas mesin 1.000 cc dan 2WD',
            'asset_date_of_entry' => '2024-04-20 00:34:28',
            'asset_quantity' => 3,
            'receipt_number' => 2123,
            'maintenance_desc' => 'Setiap hari periksa kebersihan, bensin dan kondisi',
            'created_by' => 2,
            'updated_by' => 1,
            'asset_image' => null,
            'asset_code' => '170.03.01',
            'asset_price' => '49000000',
        ]);
        
        Asset::create([
            'asset_name' => 'Motor',
            'category_id' => 7,
            'asset_type' => 'Yamaha NMAX',
            'asset_position' => 'Gedung B, Ruangan Garasi A',
            'asset_desc' => 'Yamaha NMAX, muat 2 orang penumpang, kapasitas mesin 155 cc',
            'asset_date_of_entry' => '2024-05-10 10:00:00',
            'asset_quantity' => 5,
            'receipt_number' => 2124,
            'maintenance_desc' => 'Setiap hari periksa bensin, kebersihan dan kondisi',
            'created_by' => 3,
            'updated_by' => 2,
            'asset_image' => null,
            'asset_code' => '170.03.02',
            'asset_price' => '29000000',
        ]);
        Asset::create([
            'asset_name' => 'Kotak Peralatan',
            'category_id' => 5,
            'asset_type' => 'Kotak Besi',
            'asset_position' => 'Gedung A, Ruangan Garasi A',
            'asset_desc' => 'Kotak peralatan bengkel',
            'asset_date_of_entry' => '2024-05-10 10:00:00',
            'asset_quantity' => 5,
            'receipt_number' => 2124,
            'maintenance_desc' => 'Setiap minggu periksa kebersihan, kelengkapan dan kondisi',
            'created_by' => 3,
            'updated_by' => 2,
            'asset_image' => null,
            'asset_code' => '170.07.02',
            'asset_price' => '29000000',
        ]);
        Asset::create([
            'asset_name' => 'Kotak Peralatan',
            'category_id' => 5,
            'asset_type' => 'Kotak Besi',
            'asset_position' => 'Gedung A, Ruangan Garasi ',
            'asset_desc' => 'Kotak peralatan partisi',
            'maintenance_desc' => 'Setiap hari periksa kebersihan, kelengkapan dan kondisi',
            'asset_date_of_entry' => '2024-05-10 10:00:00',
            'asset_quantity' => 5,
            'receipt_number' => 2124,
            'created_by' => 3,
            'updated_by' => 2,
            'asset_image' => null,
            'asset_code' => '170.07.02',
            'asset_price' => '29000000',
        ]);
        Asset::create([
            'asset_name' => 'Server Conditioner',
            'category_id' => 5,
            'asset_type' => 'Kipas',
            'asset_position' => 'Gedung A, Lantai 1 di Server Data',
            'asset_desc' => '1U Fan Server CPU Cooler P301-1 HCFB1 LGA 1366/2011/115X denngan kipas dan keramik penjaga suhu',
            'asset_date_of_entry' => '2024-05-10 10:00:00',
            'maintenance_desc' => 'Setiap minggu periksa kebersihan, suhu, memori dan kondisi',
            'asset_quantity' => 5,
            'receipt_number' => 2124,
            'created_by' => 3,
            'updated_by' => 2,
            'asset_image' => null,
            'asset_code' => '170.07.02',
            'asset_price' => '350000',
        ]);

        

        // Asset::factory()->count(20)->create();
        User::factory()->count(30)->create();
        User::factory()->count(20)->admin()->create();
        Loan::factory()->count(30)->create();
    }
}
