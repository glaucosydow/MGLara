@extends('layouts.default')
@section('content')
<h1 class="header">Enviar imagem</h1>
<hr>
@include('errors.form_error')
{!! Form::model($model, [
    'method' => 'POST', 
    'class' => 'form-horizontal', 
    'id' => 'form-imagem-produto', 
    'action' => ['ImagemController@produtoStore', $model->codproduto, 'imagem' => $request->get('imagem')],
    'files' => true 
]) !!}

<div class="form-group">
    <label for="usuario" class="col-sm-2 control-label">
        {!! Form::label('Imagem: ') !!}
    </label>    
    <div class="col-md-3 col-xs-4">
        <input type="file" id="imagem" name="imagem" accept="image/*">
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}
    </div>
</div>    
{!! Form::close() !!}   
@stop