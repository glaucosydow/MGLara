<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\SecaoProduto;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\GrupoProduto;
use Carbon\Carbon;

class FamiliaProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new FamiliaProduto();
        $parent = SecaoProduto::findOrFail($request->get('codsecaoproduto'));
        return view('familia-produto.create', compact('model', 'request', 'parent'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new FamiliaProduto($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->codsecaoproduto = $request->get('codsecaoproduto');
        $model->save();
        Session::flash('flash_success', 'Família Criada!');
        return redirect("familia-produto/$model->codfamiliaproduto");    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = FamiliaProduto::find($id);
        $grupos = GrupoProduto::filterAndPaginate(
            $request->get('codgrupoproduto'),
            $id,
            $request->get('grupoproduto'),
            $request->get('inativo')
        );
        return view('familia-produto.show', compact('model', 'grupos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = FamiliaProduto::findOrFail($id);
        return view('familia-produto.edit',  compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = FamiliaProduto::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        $model->save();
        
        Session::flash('flash_success', "Família '{$model->familiaproduto}' Atualizada!");
        return redirect("familia-produto/$model->codfamiliaproduto"); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $model = FamiliaProduto::find($id);
            $model->delete();
            Session::flash('flash_success', "Família '{$model->familiaproduto}' Excluida!");
            return redirect("secao-produto/$model->codsecaoproduto");
        }
        catch(\Exception $e){
            Session::flash('flash_danger', "Impossível Excluir!");
            Session::flash('flash_danger_detail', $e->getMessage());
            return redirect("secao-produto/$id"); 
        }     
    }
    
    public function inativo(Request $request)
    {
        $model = FamiliaProduto::find($request->get('codfamiliaproduto'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Família '{$model->familiaproduto}' Reativada!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Família '{$model->familiaproduto}' Inativada!";
        }
        
        $model->save();
        Session::flash('flash_success', $msg);
    }    
    
}
