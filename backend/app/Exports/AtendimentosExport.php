<?php

namespace App\Exports;

use App\Atendimento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class AtendimentosExport implements FromCollection,
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
        return Atendimento::all();
    }
    public function map($atendimento):array{
        return[
            $atendimento->id,
            $atendimento->data_hora_ligacao,
            $atendimento->isolamento,
            $atendimento->orientacao,
            $atendimento->apetite,
            $atendimento->febre,
            $atendimento->tosse,
            $atendimento->falta_de_ar,
            $atendimento->orientacao_conduta,
        ];
    }
    public function headings():array{
        return[
            'Id',
            'Data/hora ligação',
            'Isolamento',
            'Orientacao',
            'Apetite',
            'Febre',
            'Tosse',
            'Falta de ar',
            'Orientacao_conduta'
        ];
    }
    public function registerEvents():array{
        return[
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font'=>[
                        'bold'=>true
                    ]
                ]);
            }
        ];
    }
}
