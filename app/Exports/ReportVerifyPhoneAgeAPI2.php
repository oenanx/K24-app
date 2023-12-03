<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReportVerifyPhoneAgeAPI2 implements FromView, WithColumnWidths
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
        return view('exports.rptVerifyPhoneAgeApi2', [
            'periode' => $this->periode,
            'customerno' => $this->customerno,
            'data' => $this->data
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 40,            
            'C' => 15,            
            'D' => 25,            
            'E' => 15,            
            'F' => 12,            
            'G' => 15,            
            'H' => 12,            
        ];
    }
}
