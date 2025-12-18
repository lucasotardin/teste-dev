<?php
// app/Http/Requests/LivroStoreRequest.php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class LivroStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
        'nome' => 'required|string|max:255',
        'autor' => 'required|string|max:255',
        'categoria' => 'required|string',
        'codigo' => 'required|string|unique:livros,codigo',
        'tipo' => 'required|string|in:Digital,Físico',
        
        // MUDE AQUI: De 'string' para 'numeric'
        'tamanho' => 'required|numeric', 
    ];
    }
}