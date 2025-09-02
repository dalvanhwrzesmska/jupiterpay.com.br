<?php

namespace App\Http\Controllers\Gerencia;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\GerenteApoio;
use Illuminate\Http\Request;

class ApoioController extends Controller
{
    public function index(Request $request)
    {
        $porcentagem = App::first()->gerente_percentage;
        $gerente_active = App::first()->gerente_active;
        $apoios = GerenteApoio::get();
        return view('admin.ajustes.apoio', compact('apoios', 'porcentagem', 'gerente_active'));
    }

    public function create(Request $request)
    {
        $payload = [
            'titulo' => $request->input('titulo'),
            'descricao' => $request->input('descricao')
        ];
    
        $imageFields = ['imagem'];
        $allowedExtensions = ['png', 'jpeg', 'jpg', 'pdf'];
    
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $extension = strtolower($file->getClientOriginalExtension());
    
                if (!in_array($extension, $allowedExtensions)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Extensão de arquivo não permitida! Envie um arquivo png, jpeg, jpg ou pdf.'
                    ], 400);
                }
    
                $filename = uniqid() . '.' . $extension;
                $destination = public_path('uploads/material-apoio');
                if (!file_exists($destination)) {
                    mkdir($destination, 0775, true);
                }
    
                $file->move($destination, $filename);
    
                // Caminho acessível via navegador
                $payload[$field] = '/uploads/material-apoio/' . $filename;
            } else {
                unset($payload[$field]);
            }
        }
    
        GerenteApoio::create($payload);
        return response()->json(['success' => true, 'message' => 'Material criado com sucesso.']);
    }
    public function update($id, Request $request)
    {
        $payload = [
            'titulo' => $request->input('titulo'),
            'descricao' => $request->input('descricao')
        ];
    
        $imageFields = ['imagem'];
        $allowedExtensions = ['png', 'jpeg', 'jpg', 'pdf'];
    
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $extension = strtolower($file->getClientOriginalExtension());
    
                if (!in_array($extension, $allowedExtensions)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Extensão de arquivo não permitida! Envie um arquivo png, jpeg, jpg ou pdf.'
                    ], 400);
                }
    
                $filename = uniqid() . '.' . $extension;
                $destination = public_path('uploads/material-apoio');
                if (!file_exists($destination)) {
                    mkdir($destination, 0775, true);
                }
    
                $file->move($destination, $filename);
    
                // Caminho acessível via navegador
                $payload[$field] = '/uploads/material-apoio/' . $filename;
            } else {
                unset($payload[$field]);
            }
        }
    
        GerenteApoio::find($id)->update($payload);
        return response()->json(['success' => true, 'message' => 'Material alterado com sucesso.']);
    }
    public function destroy(Request $request)
    {
        //
    }
}
