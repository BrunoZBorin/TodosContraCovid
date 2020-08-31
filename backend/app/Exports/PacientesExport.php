<?php

namespace App\Exports;

use App\Paciente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class PacientesExport implements FromCollection,
ShouldAutoSize, 
WithMapping, 
WithHeadings,
WithEvents
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Paciente::all();
    }
    public function map($paciente):array{
        return[
            $paciente->nome,
            $paciente->data_nasc,
            $paciente->cns,
            $paciente->resultado_exame,
            $paciente->telefone,
            $paciente->convenio,
        ];
    }
    public function headings():array{
        return[
            'Nome',
            'Data Nascimento',
            'CNS',
            'Resultado Exame',
            'Telefone',
            'Convenio',
        ];
    }
    public function registerEvents():array{
        return[
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A1:')->applyFromArray([
                    'font'=>[
                        'bold'=>true
                    ]
                ]); 
                
            }
        ];
    }
}
