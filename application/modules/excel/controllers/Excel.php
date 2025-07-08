<?php

require_once FCPATH . 'vendor\vendor\autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel extends MY_Controller
{
    public $viewFolder = '';

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = 'excel';

        $this->load->model('girdikontrol/girdi_kontrol_model');
        $this->load->model('proseskontrol/proses_kontrol_model');
        $this->load->model('finalkontrol/final_kontrol_model');
        $this->load->model('tedarikciler/tedarikciler_model');
        $this->load->model('malzemeler/malzemeler_model');
        $this->load->model('urunler/urunler_model');
        $this->load->model('kontrol_no/kontrol_no_model');

        // $this->load->model("excel/excel_model");

        if (!get_active_user()) {
            redirect(base_url('login'));
        }
        if (!isAllowedViewModule()) {
            redirect(base_url());
        }
    }

    public function index()
    {
        $viewData = new stdClass();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');

        echo APPPATH;
        die();
        // Tablodan Verilerin Getirilmesi..

        // View'e gönderilecek Değişkenlerin Set Edilmesi..
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = 'list';
        $viewData->items = $items;

        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }

    public function girdi_kontrol()
    {
        $viewData = new stdClass();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Tempar ')
            ->setLastModifiedBy('Kalite Kontrol')
            ->setTitle('Girdi Kontrol Verileri')
            ->setSubject('Girdi Kontrol')
            ->setDescription('Girdi Kontrol Dosyasının Oluşturulması');
        // add style to the header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => '333333'],
                ],
            ],
            'fill' => [
                'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
                'startcolor' => ['rgb' => '0d0d0d'],
                'endColor'   => ['rgb' => 'f2f2f2'],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'J') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        // set the names of header cells
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'parti_no');
        $sheet->setCellValue('C1', 'hpk');
        $sheet->setCellValue('D1', 'tedarikci');
        $sheet->setCellValue('E1', 'malzeme');
        $sheet->setCellValue('F1', 'irsaliye');
        $sheet->setCellValue('G1', 'tarih');
        $sheet->setCellValue('H1', 'kullanici');
        $sheet->setCellValue('I1', 'kontrol_no');
        $sheet->setCellValue('J1', 'aciklama');
        $sheet->setCellValue('K1', 'sonuc');
        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->girdi_kontrol_model->listele();
        // Add some data
        $x = 2;
        foreach ($items as $get) {
            $sheet->setCellValue('A' . $x, $get->id);
            $sheet->setCellValue('B' . $x, $get->parti_no);
            $sheet->setCellValue('C' . $x, $get->hpk);
            $sheet->setCellValue('D' . $x, $get->tedarikci_adi);
            $sheet->setCellValue('E' . $x, $get->malzeme_adi);
            $sheet->setCellValue('F' . $x, $get->irsaliye);
            $sheet->setCellValue('G' . $x, $get->tarih);
            $sheet->setCellValue('H' . $x, $get->kullanici_adi);
            $sheet->setCellValue('I' . $x, $get->kontrol_no);
            $sheet->setCellValue('J' . $x, $get->aciklama);
            $sheet->setCellValue('K' . $x, $get->sonuc_adi);
            ++$x;
        }
        //Create file excel.xlsx
        $writer = new Xlsx($spreadsheet);

        $name = 'uploads/excel/Girdi_Kontrol_' . date('Y-m-d H-i-s') . '.xlsx';
        $writer->save($name);
        //End Function index
        echo "<a href='" . base_url($name) . "' class='btn btn-primary'>Excel İndir</a>";
    }

    public function proses_kontrol()
    {
        $viewData = new stdClass();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Tempar ')
            ->setLastModifiedBy('Kalite Kontrol')
            ->setTitle('Proses Kontrol Verileri')
            ->setSubject('Proses Kontrol')
            ->setDescription('Proses Kontrol Dosyasının Oluşturulması');
        // add style to the header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => '333333'],
                ],
            ],
            'fill' => [
                'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
                'startcolor' => ['rgb' => '0d0d0d'],
                'endColor'   => ['rgb' => 'f2f2f2'],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'L') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        // set the names of header cells
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'parti_no');
        // $sheet->setCellValue('C1', 'Muşteri');
        $sheet->setCellValue('C1', 'tarih');
        $sheet->setCellValue('D1', 'kullanici');
        $sheet->setCellValue('E1', 'urun');
        $sheet->setCellValue('F1', 'lot');
        $sheet->setCellValue('G1', 'kontrol_no');
        $sheet->setCellValue('H1', 'aciklama');
        $sheet->setCellValue('I1', 'sonuc');
        $sheet->setCellValue('J1', 'HPK');
        $sheet->setCellValue('K1', 'Klips Hammadde Parti kodu');
        $sheet->setCellValue('L1', 'Buji Braket Lot No:');
        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->proses_kontrol_model->listele();

        // Add some data
        $x = 2;
        foreach ($items as $get) {
            $sheet->setCellValue('A' . $x, $get->id);
            $sheet->setCellValue('B' . $x, $get->parti_no);
            // $sheet->setCellValue('C'.$x, $get->musteri_adi);
            $sheet->setCellValue('C' . $x, $get->tarih);
            $sheet->setCellValue('D' . $x, $get->kullanici_adi);
            $sheet->setCellValue('E' . $x, $get->urun_adi);
            $sheet->setCellValue('F' . $x, $get->lot);
            $sheet->setCellValue('G' . $x, $get->kontrol_no);
            $sheet->setCellValue('H' . $x, $get->aciklama);
            $sheet->setCellValue('I' . $x, $get->sonuc_adi);
            $sheet->setCellValue('J' . $x, $get->hpk);
            $sheet->setCellValue('K' . $x, $get->klips_hpk);
            $sheet->setCellValue('L' . $x, $get->buji_braket_lot);
            ++$x;
        }
        //Create file excel.xlsx
        $writer = new Xlsx($spreadsheet);
        $name = 'uploads/excel/Proses_Kontrol_' . date('Y-m-d H-i-s') . '.xlsx';
        $writer->save($name);
        //End Function index
        echo "<a href='" . base_url($name) . "' class='btn btn-primary'>Excel İndir</a>";
    }

    public function final_kontrol()
    {
        $viewData = new stdClass();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Tempar ')
            ->setLastModifiedBy('Kalite Kontrol')
            ->setTitle('Final Kontrol Verileri')
            ->setSubject('Final Kontrol')
            ->setDescription('Final Kontrol Dosyasının Oluşturulması');
        // add style to the header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => '333333'],
                ],
            ],
            'fill' => [
                'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
                'startcolor' => ['rgb' => '0d0d0d'],
                'endColor'   => ['rgb' => 'f2f2f2'],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'M') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        // set the names of header cells
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'kutu_no');
        // $sheet->setCellValue('C1', 'tedarikci');
        // $sheet->setCellValue('D1', 'malzeme');
        // $sheet->setCellValue('E1', 'irsaliye');
        $sheet->setCellValue('C1', 'tarih');
        $sheet->setCellValue('D1', 'kullanici');
        $sheet->setCellValue('E1', 'urun');
        $sheet->setCellValue('F1', 'lot');
        $sheet->setCellValue('G1', 'kontrol_no');
        $sheet->setCellValue('H1', 'kutu_no');
        $sheet->setCellValue('I1', 'aciklama');
        $sheet->setCellValue('J1', 'sonuc');
        $sheet->setCellValue('K1', 'Klips Montaj Lot No');
        $sheet->setCellValue('L1', 'Brülör Lot No');

        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->final_kontrol_model->listele();

        // Add some data
        $x = 2;
        foreach ($items as $get) {
            $sheet->setCellValue('A' . $x, $get->id);
            $sheet->setCellValue('B' . $x, $get->kutu_no);
            // $sheet->setCellValue('C'.$x, $get->tedarikci_adi);
            // $sheet->setCellValue('D'.$x, $get->malzeme_adi);
            // $sheet->setCellValue('E'.$x, $get->irsaliye);
            $sheet->setCellValue('C' . $x, $get->tarih);
            $sheet->setCellValue('D' . $x, $get->kullanici_adi);
            $sheet->setCellValue('E' . $x, $get->urun_adi);
            $sheet->setCellValue('F' . $x, $get->lot);
            $sheet->setCellValue('G' . $x, $get->kontrol_no);
            $sheet->setCellValue('H' . $x, $get->kutu_no);
            $sheet->setCellValue('I' . $x, $get->aciklama);
            $sheet->setCellValue('J' . $x, $get->sonuc_adi);
            $sheet->setCellValue('K' . $x, $get->klips_montaj_lot);
            $sheet->setCellValue('L' . $x, $get->brulor_lot);
            ++$x;
        }
        //Create file excel.xlsx
        $writer = new Xlsx($spreadsheet);

        $name = 'uploads/excel/Final_Kontrol_' . date('Y-m-d H-i-s') . '.xlsx';
        $writer->save($name);
        //End Function index
        echo "<a href='" . base_url($name) . "' class='btn btn-primary'>Excel İndir</a>";
    }

    public function kontrol_no()
    {
        $viewData = new stdClass();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Tempar ')
            ->setLastModifiedBy('Kalite Kontrol')
            ->setTitle('Final Kontrol Verileri')
            ->setSubject('Kontrol No')
            ->setDescription(' Kontrol No Dosyasının Oluşturulması');
        // add style to the header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => '333333'],
                ],
            ],
            'fill' => [
                'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
                'startcolor' => ['rgb' => '0d0d0d'],
                'endColor'   => ['rgb' => 'f2f2f2'],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'F') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        // set the names of header cells
        $sheet->setCellValue('A1', 'kontrol_no');
        $sheet->setCellValue('B1', 'process_isim');
        $sheet->setCellValue('C1', 'parti_no');
        $sheet->setCellValue('D1', 'lot_no');
        $sheet->setCellValue('E1', 'kutu_no');
        $sheet->setCellValue('F1', 'tarih');

        /** Tablodan Verilerin Getirilmesi.. */
        $items = $this->kontrol_no_model->get_all(
            [],
            'id ASC'
        );

        // Add some data
        $x = 2;
        foreach ($items as $get) {
            $sheet->setCellValue('A' . $x, $get->id);
            $sheet->setCellValue('B' . $x, $get->process_isim);
            $sheet->setCellValue('C' . $x, $get->parti_no);
            $sheet->setCellValue('D' . $x, $get->lot_no);
            $sheet->setCellValue('E' . $x, $get->kutu_no);
            $sheet->setCellValue('F' . $x, $get->tarih);
            ++$x;
        }
        //Create file excel.xlsx
        $writer = new Xlsx($spreadsheet);

        $name = 'uploads/excel/Kontrol_No' . date('Y-m-d H-i-s') . '.xlsx';
        $writer->save($name);
        //End Function index
        echo "<a href='" . base_url($name) . "' class='btn btn-primary'>Excel İndir</a>";
    }
    //End Class Welcome
}
