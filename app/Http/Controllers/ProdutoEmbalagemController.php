<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\ProdutoEmbalagem;
use MGLara\Models\Produto;

class ProdutoEmbalagemController extends Controller
{
    public function __construct()
    {
        $this->datas = [];
        $this->numericos = [];
    }         
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new ProdutoEmbalagem();
        $produto = Produto::findOrFail($request->codproduto);
        return view('produto-embalagem.create', compact('model', 'produto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->converteNumericos([
            'preco' => $request->input('preco'),
            'quantidade' => $request->input('quantidade')
        ]);
        $model = new ProdutoEmbalagem($request->all());
        $model->codproduto = $request->input('codproduto');
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
        return redirect("produto/$model->codproduto");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = ProdutoEmbalagem::findOrFail($id);
        $produto = $model->produto;
        return view('produto-embalagem.edit',  compact('model', 'produto'));
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
        $this->converteNumericos([
            'preco' => $request->input('preco'),
            'quantidade' => $request->input('quantidade')
        ]);
        
        $model = ProdutoEmbalagem::findOrFail($id);
        $model->fill($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_update', 'Registro atualizado.');
        return redirect("produto/$model->codproduto");        
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
            ProdutoEmbalagem::find($id)->delete();
            Session::flash('flash_delete', 'Registro deletado!');
            //return Redirect::route('');
        }
        catch(\Exception $e){
            return view('errors.fk');
        }     
    }
}
