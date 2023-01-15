<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules(){
        return [
        'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
        'imagem' => 'required|file|mimes:png,jpg,jpeg'
        ];
    }
    public function feedback(){
        return  [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da marca já existe',
            'nome.min' => 'O nome da marca deve ter no mínimo 3 caracteres',
            'imagem.required' => 'A imagem é obrigatória',
            'imagem.file' => 'O arquivo deve ser uma imagem',
            'imagem.mimes' => 'A imagem deve ser do tipo PNG, JPG ou JPEG'
        ];
    }
    public function modelos(){
        return $this->hasMany('App\Models\Modelo');
    }
}
