<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ReportValidNoAPI implements FromView, WithColumnWidths, WithColumnFormatting
{
    protected $periode;
    protected $customerno;
    protected $data;
	
    public function __construct(
        $periode,
        $customerno,
        $data
    ){
       $this->periode = $periode;
       $this->customerno = $customerno;
       $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.rptValidNoApi', [
            'periode' => $this->periode,
            'customerno' => $this->customerno,
            'data' => $this->data
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,            
            'C' => 25,            
            'D' => 25,            
            'E' => 50,            
        ];
    }
    
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => DataType::TYPE_STRING,
            'C' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'E' => DataType::TYPE_STRING,
        ];
    }
}
