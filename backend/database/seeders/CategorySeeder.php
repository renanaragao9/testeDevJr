<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Alimentos',
            'Bebidas',
            'Produtos de Limpeza',
            'Higiene Pessoal',
            'Cuidados com o Lar',
            'Pet Shop',
            'Eletrônicos e Eletrodomésticos',
            'Cereais, Grãos e Farinhas',
            'Pães e Bolos',
            'Carnes e Frios',
            'Frutas, Legumes e Verduras',
            'Doces e Sobremesas',
            'Massas',
            'Produtos de Padaria',
            'Refrigerantes',
            'Sucos',
            'Águas',
            'Bebidas Alcoólicas (Cervejas, Vinhos, Destilados)',
            'Sabão e Detergentes',
            'Desinfetantes',
            'Limpadores Multiuso',
            'Água Sanitária',
            'Shampoos e Condicionadores',
            'Sabonetes',
            'Desodorantes',
            'Cremes Dentais',
            'Utensílios Domésticos',
            'Organizadores',
            'Ferramentas',
            'Rações',
            'Acessórios para Animais',
            'Pequenos Eletrodomésticos',
            'Acessórios Eletrônicos',
        ];
        
        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
    }
}
