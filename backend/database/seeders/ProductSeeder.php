<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [];

        // Alimentos (category_id: 1)
        $alimentos = [
            'Arroz Branco', 'Feijão Carioca', 'Macarrão Espaguete', 'Farinha de Trigo', 'Óleo de Soja', 
            'Açúcar Refinado', 'Leite Integral', 'Margarina', 'Biscoito Cream Cracker', 'Café Moído'
        ];

        // Bebidas (category_id: 2)
        $bebidas = [
            'Coca-Cola 2L', 'Suco de Laranja Integral', 'Água Mineral com Gás', 'Refrigerante Guaraná', 
            'Vinho Tinto Seco', 'Cerveja Pilsen 350ml', 'Suco de Uva Concentrado', 'Chá Mate', 'Isotônico', 
            'Café Gelado'
        ];

        // Produtos de Limpeza (category_id: 3)
        $limpeza = [
            'Detergente Líquido Neutro', 'Sabão em Pó', 'Água Sanitária', 'Desinfetante Lavanda', 'Limpador Multiuso',
            'Esponja de Aço', 'Sabão Líquido para Roupas', 'Amaciante de Roupas', 'Saco de Lixo 30L', 'Desengordurante'
        ];

        // Higiene Pessoal (category_id: 4)
        $higiene = [
            'Shampoo Anticaspa', 'Sabonete em Barra', 'Desodorante Aerosol', 'Papel Higiênico Neutro', 
            'Creme Dental', 'Hidratante Corporal', 'Protetor Solar FPS 50', 'Álcool Gel', 'Cotonete', 
            'Escova de Dentes'
        ];

        // Cuidados com o Lar (category_id: 5)
        $cuidados_lar = [
            'Esponja de Limpeza Multiuso', 'Pano de Microfibra', 'Rodo de Limpeza', 'Vassoura de Piaçava', 
            'Balde Plástico', 'Saco para Aspirador', 'Pano de Chão', 'Escova de Limpeza', 'Sabão em Barra', 
            'Água de Passar'
        ];

        // Pet Shop (category_id: 6)
        $pet = [
            'Ração para Cães Adultos', 'Ração para Gatos Filhotes', 'Biscoito para Cães', 'Areia Sanitária para Gatos', 
            'Brinquedo para Cães Bola de Borracha', 'Coleira para Cães', 'Shampoo para Cães', 'Antipulgas', 
            'Comedouro para Cães', 'Ração Úmida para Gatos'
        ];

        // Eletrônicos e Eletrodomésticos (category_id: 7)
        $eletronicos = [
            'Liquidificador 500W', 'Fone de Ouvido Bluetooth', 'Ferro de Passar Roupa', 'Batedeira Portátil', 
            'Microondas 20L', 'Cafeteira Elétrica', 'Televisão LED 32"', 'Aspirador de Pó', 'Carregador Portátil', 
            'Mouse Sem Fio'
        ];

        // Função para gerar uma quantidade variada de produtos
        function generateProducts($items, $category_id, &$products, $count) {
            for ($i = 0; $i < $count; $i++) {
                $randomItem = $items[array_rand($items)];
                $products[] = [
                    'name' => $randomItem . ' ' . ($i + 1),
                    'description' => 'Descrição detalhada do ' . strtolower($randomItem),
                    'price' => rand(100, 10000) / 100, // Preço entre R$ 1,00 e R$ 100,00
                    'qtd' => rand(10, 500), // Quantidade entre 10 e 500 unidades
                    'category_id' => $category_id
                ];
            }
        }

        // Gerar 100 produtos para cada categoria
        generateProducts($alimentos, 1, $products, 100);
        generateProducts($bebidas, 2, $products, 100);
        generateProducts($limpeza, 3, $products, 100);
        generateProducts($higiene, 4, $products, 100);
        generateProducts($cuidados_lar, 5, $products, 100);
        generateProducts($pet, 6, $products, 50); // 50 para Pet Shop
        generateProducts($eletronicos, 7, $products, 50); // 50 para Eletrônicos e Eletrodomésticos

        // Inserir os produtos no banco de dados
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}


