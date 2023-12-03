<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReportValidNIK implements FromView, WithColumnWidths
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
        return view('exports.rptValidNIK', [
            'periode' => $this->periode,
            'customerno' => $this->customerno,
            'data' => $this->data
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,            
            'C' => 15,            
        ];
    }
}
