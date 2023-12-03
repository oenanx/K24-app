<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ReportValidNoBatch implements FromView, WithColumnWidths, WithColumnFormatting
{
    protected $batch_id;
    protected $customerno;
    protected $data;
	
    public function __construct(
        $batch_id,
        $customerno,
        $data
    ){
       $this->batch_id = $batch_id;
       $this->customerno = $customerno;
       $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.rptValidNoBatch', [
            'batch_id' => $this->batch_id,
            'customerno' => $this->customerno,
            'data' => $this->data
        ]);
    }

    public function columnWidths(): array
    {
        return [
			'A'	=> 50,
            'B' => 20,
            'C' => 20,
            'D' => 25,
        ];
    }
    
    public function columnFormats(): array
    {
        return [
            'A' => DataType::TYPE_STRING,
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => DataType::TYPE_STRING,
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
