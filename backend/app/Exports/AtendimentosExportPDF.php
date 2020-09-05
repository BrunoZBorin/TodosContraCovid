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

class AtendimentosExportPDF implements FromCollection,
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
			$dados = Atendimento::whereIn('usuario_id', $usuarios_id)->get();

			foreach($dados as $dado)
			{
				if($dado->orientacao_conduta == 'manter_isolamento_domiciliar')
				{
					$dado->orientacao_conduta = "Manter Isolamento Domiciliar";
				}
				else if($dado->orientacao_conduta == 'encaminhar_unidade_sintomatica')
				{
					$dado->orientacao_conduta = "Encaminhar paciente a uma unidade sintomática";
				}
				else
				{
					$dado->orientacao_conduta = "Encaminhar para o SAMU";
				}

				$dado->data_hora_ligacao = implode('/', array_reverse(explode('-', explode(' ', $dado->data_hora_ligacao)[0]))) . ' ' . explode(' ', $dado->data_hora_ligacao)[1];

				if($dado->isolamento == 'sim')
					$dado->isolamento = 'Sim';
				else
					$dado->isolamento = 'Não';

				if($dado->falta_de_ar == 'ausente')
					$dado->falta_de_ar = 'Ausente';
				else if($dado->falta_de_ar == 'presente_ao_esforco')
					$dado->falta_de_ar = 'Presente ao Esforço';
				else
					$dado->falta_de_ar = 'Intensa no Repouso';
			}

           	return $dados;
        }
        if($user->perfil=='municipal')
        {
			$dados = Atendimento::all();

			foreach($dados as $dado)
			{
				if($dado->orientacao_conduta == 'manter_isolamento_domiciliar')
				{
					$dado->orientacao_conduta = "Manter Isolamento Domiciliar";
				}
				else if($dado->orientacao_conduta == 'encaminhar_unidade_sintomatica')
				{
					$dado->orientacao_conduta = "Encaminhar paciente a uma unidade sintomática";
				}
				else
				{
					$dado->orientacao_conduta = "Encaminhar para o SAMU";
				}

				$dado->data_hora_ligacao = implode('/', array_reverse(explode('-', explode(' ', $dado->data_hora_ligacao)[0]))) . ' ' . explode(' ', $dado->data_hora_ligacao)[1];

				if($dado->isolamento == 'sim')
					$dado->isolamento = 'Sim';
				else
					$dado->isolamento = 'Não';

				if($dado->falta_de_ar == 'ausente')
					$dado->falta_de_ar = 'Ausente';
				else if($dado->falta_de_ar == 'presente_ao_esforco')
					$dado->falta_de_ar = 'Presente ao Esforço';
				else
					$dado->falta_de_ar = 'Intensa no Repouso';
			}
			
            return $dados;
        }
    }
    public function map($atendimento):array{
        return[
            $atendimento->id,
            $atendimento->data_hora_ligacao,
            $atendimento->isolamento,
            $atendimento->falta_de_ar,
            $atendimento->orientacao_conduta,
        ];
    }
    public function headings():array{
        return[
            'Id',
            'Data/Hora Ligação',
            'Isolamento',
            'Falta de Ar',
            'Orientação Conduta'
        ];
    }
    public function registerEvents():array{
        return[
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A4:I1')->applyFromArray([
                    'font'=>[
                        'bold'=>true
                    ]
                ]);
            }
        ];
    }
}
