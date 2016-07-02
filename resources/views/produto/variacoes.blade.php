<div class="panel panel-info">
    <?php
    $pvs = $model->ProdutoVariacaoS()->orderBy('variacao', 'ASC NULLS FIRST')->get();
    ?>
    <ul class="list-group group-list-striped group-list-hover">
        @foreach ($pvs as $pv)
            <li class="list-group-item">
                <strong>
                    @if (!empty($pv->codmarca))
                        <a href="{{ url("marca/$pv->codmarca") }}">
                            {{ $pv->Marca->marca }}
                        </a>
                    @endif
                    @if (!empty($pv->variacao))
                        {{ $pv->variacao }}
                    @else
                        <i>{ Sem Variação }</i>
                    @endif
                </strong>
                <div class="pull-right">
                    {{ $pv->referencia }}
                    &nbsp;
                    <a href="{{ url("produto-variacao/$pv->codprodutovariacao/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                    <a href="{{ url("produto-variacao/$pv->codprodutovariacao/delete") }}"><i class="glyphicon glyphicon-trash"></i></a>
                </div>
                    
                <div class="row">
                <?php
                $pbs = $pv->ProdutoBarraS()->leftJoin('tblprodutoembalagem as pe', 'pe.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem')
                   ->orderBy('pe.quantidade', 'ASC NULLS FIRST')
                   ->with('ProdutoEmbalagem')->get();
                ?>
                @foreach ($pbs as $pb)
                    <div class="col-md-4">
                        {{ $pb->barras }}
                        <small class="text-muted pull-right">
                            @if (!empty($pb->codprodutoembalagem))
                                {{ $pb->ProdutoEmbalagem->UnidadeMedida->sigla . " " . $pb->ProdutoEmbalagem->descricao }}
                            @else
                                {{ $model->UnidadeMedida->sigla }}
                            @endif
                            &nbsp;
                            <a href="{{ url("produto-barra/$pb->codprodutobarra/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
                            <a href="{{ url("produto-barra/$pb->codprodutobarra/delete") }}"><i class="glyphicon glyphicon-trash"></i></a>
                        </small>
                    </div>
                @endforeach
                </div>
            </li>
        @endforeach
    </ul>
</div>