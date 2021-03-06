@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('permissao');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('permissao/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("permissao/$model->codpermissao");?>"><span class="glyphicon glyphicon-eye-open"></span> Detalhes</a></li> 
        </ul>
    </div>
</nav>
<h1 class="header">Alterar permissão #{{$model->codpermissao}}</h1>
<hr>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal','onsubmit'=> 'onSubmit()','id'=> 'estoque-movimentoForm', 'action' => ['PermissaoController@update', $model->codpermissao] ]) !!}
    @include('errors.form_error')
    @include('permissao.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop