<?php

namespace MGLara\Jobs;

use MGLara\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use MGLara\Models\EstoqueMes;

class EstoqueGeraMovimentoProdutoVariacao extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;
    
    protected $codprodutovariacao;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($codprodutovariacao)
    {
        $this->codprodutovariacao = $codprodutovariacao;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('EstoqueGeraMovimentoProdutoVariacao', ['codprodutovariacao' => $this->codprodutovariacao]);
        
        $corte = Carbon::createFromFormat('Y-m-d H:i:s', EstoqueMes::CORTE_FISICO);
        
        $sql = "select npb.codnegocioprodutobarra
            from tblprodutobarra pb
            inner join tblnegocioprodutobarra npb on (npb.codprodutobarra = pb.codprodutobarra)
            inner join tblnegocio n on (n.codnegocio = npb.codnegocio)
            where pb.codprodutovariacao = {$this->codprodutovariacao}
            and n.lancamento >= '" . $corte->format('Y-m-d H:i:s') . "'
            order by npb.codnegocioprodutobarra
            ";
            
        $rows = DB::select($sql);
        
        foreach ($rows as $row)
            $this->dispatch((new EstoqueGeraMovimentoNegocioProdutoBarra($row->codnegocioprodutobarra))->onQueue('medium'));
        
    }
}
