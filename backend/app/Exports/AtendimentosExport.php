<?php

namespace App\Exports;

use App\Atendimento;
use Illuminate\Support\Facades\DB;
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
    protected $id;

    function __construct($id) {
            $this->id = $id;
    }

    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = DB::table('users')
            ->where('users.id',$this->id)
            ->select('users.*')
            ->first();
        $unidade_saude_id = $user->unidade_saude_id;
        
        $usuarios = DB::table('users')
            ->where('users.unidade_saude_id', $unidade_saude_id)
            ->select('users.id')
            ->get();
            $usuarios_id=[];
         foreach($usuarios as $users){
             $usuarios_id[] = $users->id;
         }
         
        if($user->perfil=='monitoramento')
        {
           return Atendimento::whereIn('usuario_id', $usuarios_id)->get();
        }
        if($user->perfil=='municipal')
        {
            return Atendimento::all();
        }
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
