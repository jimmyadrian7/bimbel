<?php
namespace Bimbel\Report\Controller;

use \Bimbel\Report\Controller\BaseReportController;

class SiswaController extends BaseReportController
{
    public function getRelation($object, $related_name, $name = 'nama')
    {
        $result = "";

        if ($object->{$related_name})
        {
            $result = $object->{$related_name}->{$name};
        }

        return $result;
    }

    public function getSiswa()
    {
        $session = new \Bimbel\Master\Model\Session();
        $siswa_ids = $session->getSiswaIds();
        $siswa = new \Bimbel\Siswa\Model\Siswa();
        
        if ($siswa_ids !== false)
        {
            $siswa = $siswa->whereIn('id', $siswa_ids);
        }
        
        $siswas = $siswa->where('status', 'a')->get();
        $header = [
            'No. Formulir', 'Tanggal Pendaftaran', 'Nama', 'Nama Mandarin', 
            'Program', 'Tempat Kursus', 'Jenis Kelamin', 'Agama', 'Tempat Lahir',
            'Tanggal Lahir', 'No. HP', 'Email', 'Alamat', 'Sekolah', 'Kelas',
            'Nama Ayah', 'Nama Ibu', 'Pekerjaan Ayah', 'Pekerjaan Ibu', 'No. HP Orang Tua',
            'Pinyin', 'Dengar', 'Bicara', 'Membaca', 'Menulis', 'Kondisi Siswa',
            'Respon & Tanggapan Orang Tua', 'Tanggapan Guru'
        ];
        $data = [$header];

        foreach ($siswas as $siswa) {
            $data_siswa = [
                $siswa->no_formulir, $this->convertDate($siswa->tanggal_pendaftaran),
                $siswa->orang->nama, $siswa->orang->nama_mandarin, $siswa->program,
                $this->getRelation($siswa, 'kursus'), $siswa->orang->getLabel('jenis_kelamin'),
                $this->getRelation($siswa->orang, 'agama'), $siswa->orang->tempat_lahir,
                $this->convertDate($siswa->orang->tanggal_lahir), $siswa->orang->no_hp,
                $siswa->orang->email, $siswa->orang->alamat, $siswa->sekolah, $siswa->kelas,
                $siswa->orang->nama_ayah, $siswa->orang->nama_ibu, $siswa->orang->pekerjaan_ayah,
                $siswa->orang->nama_ibu, $siswa->orang->no_hp_ortu,
                $siswa->pinyin, $siswa->dengar, $siswa->bicara, $siswa->membaca, $siswa->menulis,
                $siswa->kondisi, $siswa->respon, $siswa->tanggapan
            ];
            array_push($data, $data_siswa);
        }

        $excel = $this->container->get('excel');
        $excel->writeSheet($data);
        $result = $excel->writeToString();
        $result = ['data' => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }

    public function getJadwal()
    {
        $session = new \Bimbel\Master\Model\Session();
        $siswa_ids = $session->getSiswaIds();
        $siswa = new \Bimbel\Siswa\Model\Siswa();
        
        if ($siswa_ids !== false)
        {
            $siswa = $siswa->whereIn('id', $siswa_ids);
        }
        
        $siswas = $siswa->where('status', 'b')->get();
        $header = array('No. Formulir', 'Nama', 'Nama Mandarin', 'Jadwal');
        $subHeader = ['', '', '', 'Hari', 'Waktu'];
        $rowNumber = 0;

        $sheet_name = 'Jadwal';
        $excel = $this->container->get('excel');

        // Write Header
        $excel->writeSheetRow($sheet_name, $header, array('halign'=>'center'));
        $excel->writeSheetRow($sheet_name, $subHeader, array('halign'=>'center'));
        $rowNumber += 2;

        $excel->markMergedCell($sheet_name, $start_row=0, $start_col=0, $end_row=1, $end_col=0);
        $excel->markMergedCell($sheet_name, $start_row=0, $start_col=1, $end_row=1, $end_col=1);
        $excel->markMergedCell($sheet_name, $start_row=0, $start_col=2, $end_row=1, $end_col=2);
        $excel->markMergedCell($sheet_name, $start_row=0, $start_col=3, $end_row=0, $end_col=4);

        // Write Data
        foreach($siswas as $siswa)
        {
            $row = [$siswa->no_formulir, $siswa->orang->nama, $siswa->orang->nama_mandarin];
            $start_row = $rowNumber;

            if (count($siswa->jadwal) === 0)
            {
                $excel->writeSheetRow($sheet_name, $row, ['valign' => 'center']);
                $rowNumber++;
            }

            foreach($siswa->jadwal as $key => $jadwal)
            {
                if ($key === 0)
                {
                    $row = array_merge($row, [$jadwal->getLabel('hari'), $jadwal->waktu]);
                    $excel->writeSheetRow($sheet_name, $row, ['valign' => 'center']);
                }
                else
                {
                    $row = ['', '', '', $jadwal->getLabel('hari'), $jadwal->waktu];
                    $excel->writeSheetRow($sheet_name, $row);
                }
            }
            $end_row = $rowNumber + count($siswa->jadwal) - 1;
            $excel->markMergedCell($sheet_name, $start_row, $start_col=0, $end_row, $end_col=0);
            $excel->markMergedCell($sheet_name, $start_row, $start_col=1, $end_row, $end_col=1);
            $excel->markMergedCell($sheet_name, $start_row, $start_col=2, $end_row, $end_col=2);
        }

        $result = $excel->writeToString();
        $result = ['data' => base64_encode($result)];
        $result = json_encode($result);

        return $result;
    }
}