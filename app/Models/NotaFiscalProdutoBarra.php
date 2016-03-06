<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codnotafiscalprodutobarra          NOT NULL DEFAULT nextval('tblnotafiscalprodutobarra_codnotafiscalprodutobarra_seq'::regclass)
 * @property  bigint                         $codnotafiscal                      NOT NULL
 * @property  bigint                         $codprodutobarra                    NOT NULL
 * @property  bigint                         $codcfop                            NOT NULL
 * @property  varchar(100)                   $descricaoalternativa               
 * @property  numeric(14,3)                  $quantidade                         NOT NULL
 * @property  numeric(14,3)                  $valorunitario                      NOT NULL
 * @property  numeric(14,2)                  $valortotal                         NOT NULL
 * @property  numeric(14,2)                  $icmsbase                           
 * @property  numeric(14,2)                  $icmspercentual                     
 * @property  numeric(14,2)                  $icmsvalor                          
 * @property  numeric(14,2)                  $ipibase                            
 * @property  numeric(14,2)                  $ipipercentual                      
 * @property  numeric(14,2)                  $ipivalor                           
 * @property  numeric(14,2)                  $icmsstbase                         
 * @property  numeric(14,2)                  $icmsstpercentual                   
 * @property  numeric(14,2)                  $icmsstvalor                        
 * @property  varchar(4)                     $csosn                              
 * @property  bigint                         $codnegocioprodutobarra             
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  numeric(3,0)                   $icmscst                            
 * @property  numeric(3,0)                   $ipicst                             
 * @property  numeric(3,0)                   $piscst                             
 * @property  numeric(14,2)                  $pisbase                            
 * @property  numeric(4,2)                   $pispercentual                      
 * @property  numeric(14,2)                  $pisvalor                           
 * @property  numeric(3,0)                   $cofinscst                          
 * @property  numeric(14,2)                  $cofinsbase                         
 * @property  numeric(14,2)                  $cofinsvalor                        
 * @property  numeric(14,2)                  $csllbase                           
 * @property  numeric(4,2)                   $csllpercentual                     
 * @property  numeric(14,2)                  $csllvalor                          
 * @property  numeric(14,2)                  $irpjbase                           
 * @property  numeric(4,2)                   $irpjpercentual                     
 * @property  numeric(14,2)                  $irpjvalor                          
 * @property  numeric(4,2)                   $cofinspercentual                   
 *
 * Chaves Estrangeiras
 * @property  Cfop                           $Cfop                          
 * @property  NegocioProdutoBarra            $NegocioProdutoBarra           
 * @property  NotaFiscal                     $NotaFiscal                    
 * @property  ProdutoBarra                   $ProdutoBarra                  
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  EstoqueMovimento[]             $EstoqueMovimentoS
 */


class NotaFiscalProdutoBarra extends MGModel
{
    protected $table = 'tblnotafiscalprodutobarra';
    protected $primaryKey = 'codnotafiscalprodutobarra';
    protected $fillable = [
        'codnotafiscal',
        'codprodutobarra',
        'codcfop',
        'descricaoalternativa',
        'quantidade',
        'valorunitario',
        'valortotal',
        'icmsbase',
        'icmspercentual',
        'icmsvalor',
        'ipibase',
        'ipipercentual',
        'ipivalor',
        'icmsstbase',
        'icmsstpercentual',
        'icmsstvalor',
        'csosn',
        'codnegocioprodutobarra',
        'icmscst',
        'ipicst',
        'piscst',
        'pisbase',
        'pispercentual',
        'pisvalor',
        'cofinscst',
        'cofinsbase',
        'cofinsvalor',
        'csllbase',
        'csllpercentual',
        'csllvalor',
        'irpjbase',
        'irpjpercentual',
        'irpjvalor',
        'cofinspercentual',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    // Chaves Estrangeiras
    public function Cfop()
    {
        return $this->belongsTo(Cfop::class, 'codcfop', 'codcfop');
    }

    public function NegocioProdutoBarra()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function NotaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'codnotafiscal', 'codnotafiscal');
    }

    public function ProdutoBarra()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codnotafiscalprodutobarra', 'codnotafiscalprodutobarra');
    }

    
    
    public function quantidadeUnitaria()
    {
        return $this->ProdutoBarra->converteQuantidade($this->quantidade);
    }
    
    public function recalculaEstoque()
    {
        $ems = $this->EstoqueMovimentoS;
        
        if ((!empty($this->NotaFiscal->nfecancelamento))
            || (!empty($this->NotaFiscal->nfeinutilizacao)))
        {
            //Apaga movimentos gerados por notas canceladas
            foreach ($ems as $em)
                $em->delete();
            
            //retorna
            return true;
        }

        //Se houver mais re um registro para o mesmo registro da nota, apaga excedentes
        for ($i=1; $i<sizeof($ems); $i++)
            $ems[$i]->delete();
        
        //se nao existe movimento, cria novo
        if (sizeof($ems) == 0)
            $em = new EstoqueMovimento;
        else
            $em = $ems[0];
        
        $em->codnegocioprodutobarra = null;
        $em->codnotafiscalprodutobarra = $this->codnotafiscalprodutobarra;
        $mes = EstoqueMes::buscaOuCria($this->ProdutoBarra->codproduto, $this->NotaFiscal->codestoquelocal, true, $this->NotaFiscal->saida);
        $em->codestoquemes = $mes->codestoquemes;
        $em->manual = false;
        $em->data = $this->NotaFiscal->saida;
        
        $em->codestoquemovimentotipo = $this->NotaFiscal->NaturezaOperacao->codestoquemovimentotipo;
        
        $quantidade = $this->quantidadeUnitaria();
        
        $valor = 0;
        
        switch ($em->EstoqueMovimentoTipo->preco)
        {
                
            case EstoqueMovimentoTipo::PRECO_MEDIO;
                $valor = $em->EstoqueMes->saldovalorunitario;
                break;
            
            case EstoqueMovimentoTipo::PRECO_ORIGEM:
                
                $nfechave = $this->NotaFiscal->nfechave;
                $valor = 0;
                
                // Procura NF Origem baseado na chave
                $nfsOrigem = NotaFiscal
                    ::where('nfechave', $nfechave)
                    ->where('codnotafiscal', '!=', $this->NotaFiscal->codnotafiscal)
                    ->where('codnaturezaoperacao', '=', $this->NotaFiscal->NaturezaOperacao->codnaturezaoperacaodevolucao)
                    ->get();
                
                // se nao achou a nota desiste
                if (sizeof($nfsOrigem) == 0)
                    break;
                
                // percorre as notas de origem
                foreach ($nfsOrigem as $nfOrigem)
                {
                    
                    // procura um item com a mesma quantidade
                    $nfpbsOrigem = NotaFiscalProdutoBarra
                        ::where('codnotafiscal', $nfOrigem->codnotafiscal)
                        ->where('codprodutobarra', $this->codprodutobarra)
                        ->where('quantidade', $this->quantidade)
                        ->get();

                    //se nao achou procura somente pelo codigo
                    if (sizeof($nfpbsOrigem) == 0)
                        $nfpbsOrigem = NotaFiscalProdutoBarra
                            ::where('codnotafiscal', $nfOrigem->codnotafiscal)
                            ->where('codprodutobarra', $this->codprodutobarra)
                            ->get();

                    //se nao achou origem desiste
                    if (sizeof($nfpbsOrigem) == 0)
                    {
                        echo $this->NotaFiscal->codnotafiscal . '<hr>';
                        echo $this->NotaFiscal->NaturezaOperacao->naturezaoperacao . '<hr>';
                        die();
                        break 2;
                    }
                    
                    foreach ($nfpbsOrigem[0]->EstoqueMovimentoS as $emOrigem)
                    {
                        $em->codestoquemovimentoorigem = $emOrigem->codestoquemovimento;
                        $valor = $emOrigem->EstoqueMes->saldovalorunitario;
                    }   
                    
                }
                break;
                
            
            case EstoqueMovimentoTipo::PRECO_INFORMADO:
            default:
                $valor = 
                    $this->valortotal + 
                    $this->icmsstvalor + 
                    $this->ipivalor;
                
                if ($this->NotaFiscal->valordesconto > 0)
                    $valor -= ($this->NotaFiscal->valordesconto / $this->NotaFiscal->valorprodutos) * $this->valortotal;
                
                if ($this->NotaFiscal->valorfrete > 0)
                    $valor += ($this->NotaFiscal->valorfrete / $this->NotaFiscal->valorprodutos) * $this->valortotal;
                
                if ($this->NotaFiscal->valoroutras > 0)
                    $valor += ($this->NotaFiscal->valoroutras / $this->NotaFiscal->valorprodutos) * $this->valortotal;
                
                if ($this->NotaFiscal->valorseguro > 0)
                    $valor += ($this->NotaFiscal->valorseguro / $this->NotaFiscal->valorprodutos) * $this->valortotal;
                break;
                
        }
        
        if ($this->NotaFiscal->NaturezaOperacao->codoperacao == Operacao::ENTRADA)
        {
            $em->entradaquantidade = $quantidade;
            $em->entradavalor = $valor;
            $em->saidaquantidade = null;
            $em->saidavalor = null;
        }
        else
        {
            $em->entradaquantidade = null;
            $em->entradavalor = null;
            $em->saidaquantidade = $quantidade;
            $em->saidavalor = $valor;
        }
        return $em->save();
    }
    
}