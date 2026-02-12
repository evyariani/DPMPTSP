<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use Carbon\Carbon;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'BUDI ANDRIAN SUTANTO, S. Sos, M.M',
                'nip' => '19760218 200701 1 006',
                'pangkat' => 'Pembina',
                'gol' => 'IV/a',
                'jabatan' => 'Kepala Dinas',
                'tk_jalan' => 'B2'
            ],
            [
                'nama' => 'IRMA ROSANTI, S.Sos, M.I.Kom',
                'nip' => '19730225 200604 2 006',
                'pangkat' => 'Pembina TK. I',
                'gol' => 'IV/b',
                'jabatan' => 'Sekretaris',
                'tk_jalan' => 'C'
            ],
            [
                'nama' => 'DANITA PUSPAWARDANI, SSTP, M.Si',
                'nip' => '19820822 200012 2 003',
                'pangkat' => 'Pembina',
                'gol' => 'IV/a',
                'jabatan' => 'Kabid Data, Informasi dan Pengaduan',
                'tk_jalan' => 'C'
            ],
            [
                'nama' => 'BUDI ANDRIAN SUTANTO, S. Sos, M.M',
                'nip' => '19760218 200701 1 006',
                'pangkat' => 'Pembina',
                'gol' => 'IV/a',
                'jabatan' => 'Kabid Perizinan dan Non Perizinan Tertentu',
                'tk_jalan' => 'C'
            ],
            [
                'nama' => 'M. HAYAT, S. Sos',
                'nip' => '19701013 199203 1 006',
                'pangkat' => 'Pembina',
                'gol' => 'IV/a',
                'jabatan' => 'Kabid Perizinan dan Non Perizinan Jasa Usaha',
                'tk_jalan' => 'C'
            ],
            [
                'nama' => 'EMROHAYAT S.Pt',
                'nip' => '19731110 199303 1 003',
                'pangkat' => 'Pembina',
                'gol' => 'IV/a',
                'jabatan' => 'Kabid Penanaman Modal',
                'tk_jalan' => 'C'
            ],
            [
                'nama' => 'SHELVY NURMULIAWATI, SE, M.M',
                'nip' => '19850708 201001 2 019',
                'pangkat' => 'Penata TK.I',
                'gol' => 'III/d',
                'jabatan' => 'Kasubbag Perencanaan dan Pelaporan',
                'tk_jalan' => 'D'
            ],
            [
                'nama' => 'RINNI AULIA, SE',
                'nip' => '19830619 200604 2 015',
                'pangkat' => 'Penata',
                'gol' => 'III/c',
                'jabatan' => 'Kasubbag Umum dan Kepegawaian',
                'tk_jalan' => 'D'
            ],
            [
                'nama' => 'RINI KUNTIAWATI, S.Sos',
                'nip' => null,
                'pangkat' => null,
                'gol' => null,
                'jabatan' => 'Tenaga Ahli Fasilitasi Tenaga Pendamping OSS',
                'tk_jalan' => null
            ],
            [
                'nama' => 'NURUL SOFIA BUDI, S. Sos',
                'nip' => '19770731 200701 2 009',
                'pangkat' => 'Penata Muda',
                'gol' => 'III/a',
                'jabatan' => 'Penata Kelola Sistem Dan Teknologi Informasi',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'LIDIA MIRANTI MAYASARI, SE',
                'nip' => '19840817 200903 2 022',
                'pangkat' => 'Penata Muda TK.I',
                'gol' => 'III/b',
                'jabatan' => 'Penelaah Teknis Kebijakan',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'MERLY ROESTANTY, S.Kom',
                'nip' => '19810511 200604 2 016',
                'pangkat' => 'Penata',
                'gol' => 'III/c',
                'jabatan' => 'Penata Kelola Sistem Dan Teknologi Informasi',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'RISMAN SYUHADA, SE',
                'nip' => '19841011 200901 1 003',
                'pangkat' => 'Penata Muda',
                'gol' => 'III/b',
                'jabatan' => 'Penata Kelola Sistem Dan Teknologi Informasi',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'FAHRURAJI, S.Pd.',
                'nip' => '19890427 202521 1 025',
                'pangkat' => null,
                'gol' => 'IX',
                'jabatan' => 'Penata Layanan Operasional',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'ARIYATI LESTARI, A.Md',
                'nip' => '19980529 202521 2 019',
                'pangkat' => null,
                'gol' => 'VII',
                'jabatan' => 'Pengelola Layanan Operasional',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'SITI NURHIKMAH',
                'nip' => '20000207 202521 2 005',
                'pangkat' => null,
                'gol' => 'V',
                'jabatan' => 'Pengadministrasi Perkantoran',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'HAMID ARIP, A. Md',
                'nip' => '19740222 200901 1 001',
                'pangkat' => 'Penata Muda',
                'gol' => 'III/a',
                'jabatan' => 'Plt. Kasubbag Keuangan/Verifikator',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'NURLITA FEBRIANA PRATIWI, A.Md',
                'nip' => '19980208 202012 2 007',
                'pangkat' => 'Pengatur TK. I',
                'gol' => 'II/d',
                'jabatan' => 'Bendahara Pengeluaran',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'M. NOOR SUPIANI',
                'nip' => '19850404 201101 1 003',
                'pangkat' => 'Pengatur Tingkat I',
                'gol' => 'II/d',
                'jabatan' => 'Pengadminstrasi Perkantoran',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'DWI ASHARIYANTO',
                'nip' => null,
                'pangkat' => null,
                'gol' => null,
                'jabatan' => 'PTT',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'RIDHA NORHADI, S.Sos',
                'nip' => null,
                'pangkat' => null,
                'gol' => null,
                'jabatan' => 'PTT',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'TERY MAULINDA SARI',
                'nip' => '19851209 202521 2 023',
                'pangkat' => null,
                'gol' => 'IX',
                'jabatan' => 'Penata Layanan Operasional',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'DINA ANGGRAINI, A.Md',
                'nip' => '19910107 202521 2 029',
                'pangkat' => null,
                'gol' => 'VII',
                'jabatan' => 'Pengelola Layanan Operasional',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'YEYEN ROFIQOH RAHMANIYYAH',
                'nip' => '19780612 202521 2 021',
                'pangkat' => null,
                'gol' => null,
                'jabatan' => 'Pengadministrasi Perkantoran',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'MUHAMMAD DZULFAQAR LUTHFI',
                'nip' => null,
                'pangkat' => null,
                'gol' => null,
                'jabatan' => 'Non ASN',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'PANDU SETIAWAN FAMA, SE',
                'nip' => '19900525 202521 1 031',
                'pangkat' => null,
                'gol' => 'IX',
                'jabatan' => 'Penata Layanan Operasional',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'RUSIDA RIYANI, A. Md',
                'nip' => '19980515 202521 2 021',
                'pangkat' => null,
                'gol' => 'VII',
                'jabatan' => 'Pengelola Layanan Operasional',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'RIZQIA AMALIYANI, S. Ag',
                'nip' => '19971121 202521 2 019',
                'pangkat' => null,
                'gol' => 'IX',
                'jabatan' => 'Penata Layanan Operasional',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'DEDE ZAKARIA',
                'nip' => null,
                'pangkat' => null,
                'gol' => null,
                'jabatan' => 'Non ASN',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'ZOYA ASKALDA, S, Kom',
                'nip' => '19991005 202421 1 006',
                'pangkat' => null,
                'gol' => 'IX',
                'jabatan' => 'Pranata Komputer Ahli Pertama',
                'tk_jalan' => 'E'
            ],
            [
                'nama' => 'M. RIDHO ADRIANI, S.AP',
                'nip' => '19871015 202521 1 031',
                'pangkat' => null,
                'gol' => 'IX',
                'jabatan' => 'Penata Layanan Operasional',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'RIFQI IHSANI, A.Md',
                'nip' => '19941002 202521 1 021',
                'pangkat' => null,
                'gol' => 'VII',
                'jabatan' => 'Pengelola Layanan Operasional',
                'tk_jalan' => 'F'
            ],
            [
                'nama' => 'MUHAMMAD MIHDAD FIRDAUS',
                'nip' => null,
                'pangkat' => null,
                'gol' => null,
                'jabatan' => 'PTT',
                'tk_jalan' => 'F'
            ],
        ];

        foreach ($data as $pegawai) {
            Pegawai::create($pegawai);
        }
    }
}