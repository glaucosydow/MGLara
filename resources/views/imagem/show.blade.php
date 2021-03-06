@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url('imagem') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
            </li>
            @if(empty($model->inativo))
            <li>
                <a href="" id="inativo-imagem">
                    <span class="glyphicon glyphicon-ban-circle"></span> Inativar
                </a>
            </li> 
            @endif
            @if($model->inativo)
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['imagem.destroy', $model->codimagem]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
            @endif
        </ul>
    </div>
</nav>
<h1 class="header">
    @if(!empty($model->inativo))
        <del>
    @endif
    <small>
        {{ formataCodigo($model->codimagem) }}
    </small>
    {{ $model->codimagem }}
    @if(!empty($model->inativo))
        </del>
    @endif
    @if(!empty($model->inativo))
        <small class="text-danger" >Inativo desde {{formataData($model->inativo, 'L')}}!</small>
    @endif
</h1>
@include('includes.autor')
<hr>
<div>
    <div class="col-xs-6">
    @if(empty($model->inativo))    
        <h3>Relacionamentos</h3>
        
        <hr>
        @foreach($model->GrupoProdutoS as $grupo)
        <p>
            <strong>Grupo:</strong> <a href="{{ url("grupo-produto/{$grupo->codgrupoproduto}") }}">{{ $grupo->grupoproduto }}</a>
        </p>
        @endforeach
        
        @foreach($model->MarcaS as $marca)
        <p>
            <strong>Marca:</strong> <a href="{{ url("marca/{$marca->codmarca}") }}">{{ $marca->marca }}</a>
        </p>
        @endforeach

        @foreach($model->SecaoProdutoS as $secao)
        <p>
            <strong>Seçao Produto:</strong> <a href="{{ url("secao-produto/{$secao->codsecaoproduto}") }}">{{ $secao->secaoproduto }}</a>
        </p>
        @endforeach

        @foreach($model->FamiliaProdutoS as $familia)
        <p>
            <strong>Família Produto:</strong> <a href="{{ url("familia-produto/{$familia->codfamiliaproduto}") }}">{{ $familia->familiaproduto }}</a>
        </p>
        @endforeach
       
        @foreach($model->SubGrupoProdutoS as $subgrupo)
        <p>
            <strong>Sub Grupo:</strong> <a href="{{ url("sub-grupo-produto/{$subgrupo->codsubgrupoproduto}") }}">{{ $subgrupo->subgrupoproduto }}</a>
        </p>
        @endforeach
       
        @foreach($model->ProdutoS as $produto)
        <p>
            <strong>Produto:</strong>  <a href="{{ url("produto/{$produto->codproduto}") }}">{{ $produto->produto }}</a>
        </p>
        @endforeach
        
    @endif
    </div>
    <div class="col-xs-6">
        <img class="img-responsive" src="<?php echo URL::asset('public/imagens/'.$model->observacoes);?>">
    </div>
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#inativo-imagem').on("click", function(e) {
        e.preventDefault();
        var codimagem = {{ $model->codimagem }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/imagem/inativo', {
                    codimagem: codimagem,
                    acao: acao,
                    _token: token
                }).done(function (data) {
                    location.reload();
                }).fail(function (error){
                  location.reload();          
              });
            }  
        });
    });

});
</script>
@endsection
@stop
