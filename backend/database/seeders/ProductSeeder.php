<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Garantir que temos as categorias disponíveis
        $categories = Category::all()->keyBy('name');

        // Lista de produtos para seed
        $products = [
            // Processadores
            [
                'name' => 'AMD Ryzen 5 7600X',
                'description' => '6 núcleos, 12 threads, até 5.3GHz, Socket AM5, vídeo integrado.',
                'price' => 1199.99,
                'validity_date' => '2026-05-17',
                'image' => 'amd_ryzen5_7600x.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/amd_ryzen5_7600x.jpg',
                'category' => 'Processadores'
            ],
            [
                'name' => 'Intel Core i9-14900F',
                'description' => '24 núcleos, 32 threads, até 5.8GHz, LGA1700, sem vídeo integrado.',
                'price' => 3500.00,
                'validity_date' => '2026-05-17',
                'image' => 'intel_core_i9_14900f.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/intel_core_i9_14900f.jpg',
                'category' => 'Processadores'
            ],
            [
                'name' => 'AMD Ryzen 7 5800X',
                'description' => '8 núcleos, 16 threads, até 4.7GHz, Socket AM4, sem vídeo integrado.',
                'price' => 1599.99,
                'validity_date' => '2026-05-17',
                'image' => 'amd_ryzen7_5800x.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/amd_ryzen7_5800x.jpg',
                'category' => 'Processadores'
            ],
            
            // Teclados
            [
                'name' => 'Teclado Mecânico Akko 5087S QMK',
                'description' => 'Teclado mecânico com layout 87 teclas, RGB, compatível com QMK.',
                'price' => 469.99,
                'validity_date' => '2026-05-17',
                'image' => 'teclado_akko_5087s_qmk.jpg',
                'image_url' => 'https://passeidireto.com/images/products/teclado_akko_5087s_qmk.jpg',
                'category' => 'Teclados'
            ],
            [
                'name' => 'Teclado Multilaser Warrior TC208',
                'description' => 'Teclado semi mecânico com iluminação Rainbow, layout ABNT2.',
                'price' => 127.78,
                'validity_date' => '2026-05-17',
                'image' => 'teclado_multilaser_tc208.jpg',
                'image_url' => 'https://donatec.com.br/images/products/teclado_multilaser_tc208.jpg',
                'category' => 'Teclados'
            ],
            
            // Mouses
            [
                'name' => 'Mouse Redragon Stormrage M718-RGB',
                'description' => 'Mouse com 10000 DPI, RGB, 6 botões programáveis.',
                'price' => 97.50,
                'validity_date' => '2026-05-17',
                'image' => 'mouse_redragon_stormrage.jpg',
                'image_url' => 'https://amazon.com/images/products/mouse_redragon_stormrage.jpg',
                'category' => 'Mouses'
            ],
            [
                'name' => 'Mouse Logitech G403 Hero',
                'description' => 'Mouse com sensor Hero 25K, até 25600 DPI, RGB.',
                'price' => 249.99,
                'validity_date' => '2026-05-17',
                'image' => 'mouse_logitech_g403_hero.jpg',
                'image_url' => 'https://passeidireto.com/images/products/mouse_logitech_g403_hero.jpg',
                'category' => 'Mouses'
            ],
            
            // Headsets
            [
                'name' => 'Headset Logitech G733',
                'description' => 'Headset sem fio, som surround, iluminação RGB, microfone removível.',
                'price' => 849.99,
                'validity_date' => '2026-05-17',
                'image' => 'headset_logitech_g733.jpg',
                'image_url' => 'https://bytechnet.com.br/images/products/headset_logitech_g733.jpg',
                'category' => 'Headsets'
            ],
            [
                'name' => 'Headset Motospeed G750',
                'description' => 'Headset com som 7.1 virtual, drivers de 50mm, iluminação RGB.',
                'price' => 199.99,
                'validity_date' => '2026-05-17',
                'image' => 'headset_motospeed_g750.jpg',
                'image_url' => 'https://donatec.com.br/images/products/headset_motospeed_g750.jpg',
                'category' => 'Headsets'
            ],
            
            // Memórias RAM
            [
                'name' => 'Memória Kingston Fury Beast 8GB DDR4',
                'description' => 'Módulo de memória DDR4, 8GB, 3200MHz, CL16.',
                'price' => 229.00,
                'validity_date' => '2026-05-17',
                'image' => 'memoria_kingston_fury_beast_8gb.jpg',
                'image_url' => 'https://scribd.com/images/products/memoria_kingston_fury_beast_8gb.jpg',
                'category' => 'Memórias RAM'
            ],
            [
                'name' => 'Memória HyperX Predator RGB 32GB',
                'description' => 'Kit com 2 módulos de 16GB, DDR4, 3000MHz, com iluminação RGB.',
                'price' => 800.00,
                'validity_date' => '2026-05-17',
                'image' => 'memoria_hyperx_predator_rgb_32gb.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/memoria_hyperx_predator_rgb_32gb.jpg',
                'category' => 'Memórias RAM'
            ],
            [
                'name' => 'Memória Corsair Vengeance LPX 16GB DDR4 3600MHz',
                'description' => 'Módulo de memória DDR4, 16GB, 3600MHz, CL18, baixo perfil.',
                'price' => 399.90,
                'validity_date' => '2026-05-17',
                'image' => 'memoria_corsair_vengeance_lpx_16gb.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/memoria_corsair_vengeance_lpx_16gb.jpg',
                'category' => 'Memórias RAM'
            ],
            
            // HDDs
            [
                'name' => 'HD Seagate Barracuda 2TB',
                'description' => 'Disco rígido de 2TB, 7200 RPM, SATA III, 3.5".',
                'price' => 299.99,
                'validity_date' => '2026-05-17',
                'image' => 'hdd_seagate_barracuda_2tb.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/hdd_seagate_barracuda_2tb.jpg',
                'category' => 'HDDs'
            ],
            [
                'name' => 'HD Western Digital Blue 4TB',
                'description' => 'Disco rígido de 4TB, 5400 RPM, SATA III, 3.5".',
                'price' => 499.99,
                'validity_date' => '2026-05-17',
                'image' => 'hdd_wd_blue_4tb.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/hdd_wd_blue_4tb.jpg',
                'category' => 'HDDs'
            ],
            
            // SSDs NVMe
            [
                'name' => 'SSD Kingston NV2 1TB M.2 NVMe',
                'description' => 'SSD M.2 NVMe de 1TB, leitura até 3500MB/s, gravação até 2100MB/s.',
                'price' => 259.99,
                'validity_date' => '2026-05-17',
                'image' => 'ssd_kingston_nv2_1tb.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/ssd_kingston_nv2_1tb.jpg',
                'category' => 'SSDs NVMe'
            ],
            [
                'name' => 'SSD 480GB M.2 NVMe Genérico',
                'description' => 'SSD M.2 NVMe de 480GB, ideal para upgrades de desempenho.',
                'price' => 316.20,
                'validity_date' => '2026-05-17',
                'image' => 'ssd_nvme_480gb.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/ssd_nvme_480gb.jpg',
                'category' => 'SSDs NVMe'
            ],
            [
                'name' => 'SSD Samsung 980 Pro 2TB M.2 NVMe',
                'description' => 'SSD M.2 NVMe PCIe 4.0 de 2TB, leitura até 7000MB/s, gravação até 5100MB/s.',
                'price' => 1299.99,
                'validity_date' => '2026-05-17',
                'image' => 'ssd_samsung_980_pro_2tb.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/ssd_samsung_980_pro_2tb.jpg',
                'category' => 'SSDs NVMe'
            ],
            
            // Placas de Vídeo
            [
                'name' => 'Placa de Vídeo MSI Radeon RX 570 Armor 4G',
                'description' => 'Placa de vídeo com 4GB GDDR5, ideal para jogos em Full HD.',
                'price' => 899.99,
                'validity_date' => '2026-05-17',
                'image' => 'gpu_msi_rx570_armor_4g.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/gpu_msi_rx570_armor_4g.jpg',
                'category' => 'Placas de Vídeo'
            ],
            [
                'name' => 'Placa de Vídeo NVIDIA RTX 3060 Ti',
                'description' => 'Placa de vídeo com 8GB GDDR6, excelente para jogos em alta resolução.',
                'price' => 2299.99,
                'validity_date' => '2026-05-17',
                'image' => 'gpu_nvidia_rtx_3060_ti.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/gpu_nvidia_rtx_3060_ti.jpg',
                'category' => 'Placas de Vídeo'
            ],
            [
                'name' => 'Placa de Vídeo NVIDIA GeForce RTX 4070',
                'description' => 'Placa de vídeo com 12GB GDDR6X, para jogos em 4K e Ray Tracing.',
                'price' => 3999.99,
                'validity_date' => '2026-05-17',
                'image' => 'gpu_nvidia_rtx_4070.jpg',
                'image_url' => 'https://pavonesinfo.com.br/images/products/gpu_nvidia_rtx_4070.jpg',
                'category' => 'Placas de Vídeo'
            ],
        ];

        // Criar produtos
        foreach ($products as $productData) {
            $categoryName = $productData['category'];
            unset($productData['category']);
            
            // Verificar se a categoria existe
            if (isset($categories[$categoryName])) {
                $productData['category_id'] = $categories[$categoryName]->id;
                Product::create($productData);
            }
        }
    }
}
