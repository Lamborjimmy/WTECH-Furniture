<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // roles
        DB::table('roles')->insert(['name' => 'customer']);
        DB::table('roles')->insert(['name' => 'administrator']);

        // payment options
        DB::table('payment_options')->insert(['name' => 'Platba kartou']);
        DB::table('payment_options')->insert(['name' => 'Dobierka']);
        DB::table('payment_options')->insert(['name' => 'Bankový prevod']);

        // delivery options
        DB::table('delivery_options')->insert(['name' => 'Kuriér', 'price' => 3.99]);
        DB::table('delivery_options')->insert(['name' => 'Pošta', 'price' => 2.99]);
        DB::table('delivery_options')->insert(['name' => 'Odberné miesto', 'price' => 1.99]);
        DB::table('delivery_options')->insert(['name' => 'Osobný odber', 'price' => 0.00]);

        // categories
        $categoryArmchairId = DB::table('categories')->insertGetId(['name' => 'Kreslá']);
        $categoryBedId = DB::table('categories')->insertGetId(['name' => 'Postele']);
        $categoryChairId = DB::table('categories')->insertGetId(['name' => 'Stoličky']);
        $categorySofaId = DB::table('categories')->insertGetId(['name' => 'Pohovky']);
        $categoryTableId = DB::table('categories')->insertGetId(['name' => 'Stoly']);
        $categoryWardrobeId = DB::table('categories')->insertGetId(['name' => 'Skrine']);
        
        // colors
        $colorBlackId = DB::table('colors')->insertGetId(['name' => 'Čierna', 'hex_string' => '#000000']);
        $colorBeigeId = DB::table('colors')->insertGetId(['name' => 'Béžová', 'hex_string' => '#F5F5DC']);
        $colorWhiteId = DB::table('colors')->insertGetId(['name' => 'Biela', 'hex_string' => '#FFFFFF']);
        $colorYellowId = DB::table('colors')->insertGetId(['name' => 'Žltá', 'hex_string' => '#FFFF00']);
        $colorBrownId = DB::table('colors')->insertGetId(['name' => 'Hnedá', 'hex_string' => '#A52A2A']);
        $colorGreyId = DB::table('colors')->insertGetId(['name' => 'Sivá', 'hex_string' => '#808080']);
        $colorRedId = DB::table('colors')->insertGetId(['name' => 'Červená', 'hex_string' => '#FF0000']);
        $colorBlueId = DB::table('colors')->insertGetId(['name' => 'Modrá', 'hex_string' => '#0000FF']);
        $colorGreenId = DB::table('colors')->insertGetId(['name' => 'Zelená', 'hex_string' => '#008000']);

        // materials
        $materialWoodId = DB::table('materials')->insertGetId(['name' => 'Drevo']);
        $materialPlasticId = DB::table('materials')->insertGetId(['name' => 'Plast']);
        $materialTextilId = DB::table('materials')->insertGetId(['name' => 'Textília']);
        $materialMetalId = DB::table('materials')->insertGetId(['name' => 'Kov']);
        $materialOtherId = DB::table('materials')->insertGetId(['name' => 'Iné']);
        $materialLeatherId = DB::table('materials')->insertGetId(['name' => 'Koža']);
        
        // placements
        $placementBedroomId = DB::table('placements')->insertGetId(['name' => 'Spálňa']);
        $placementKitchenId = DB::table('placements')->insertGetId(['name' => 'Kuchyňa']);
        $placementBathroomId = DB::table('placements')->insertGetId(['name' => 'Kúpeľňa']);
        $placementLivingRoomId = DB::table('placements')->insertGetId(['name' => 'Obývačka']);
        $placementOfficeId = DB::table('placements')->insertGetId(['name' => 'Kancelária']);
        $placementExteriorId = DB::table('placements')->insertGetId(['name' => 'Exteriér']);

        // products
        $productDescription = 'Tento kus nábytku prináša dokonalú kombináciu štýlu, komfortu a funkčnosti. Navrhnutý s dôrazom na kvalitu a trvanlivosť, je ideálnou voľbou pre každý interiér. Jeho nadčasový dizajn a kvalitné materiály zaručujú, že sa hodí do rôznych miestností a štýlov zariadenia. Či už v obývacej izbe, spálni, kancelárii alebo jedálni, tento nábytok prinesie do vášho domova nielen pohodlie, ale aj estetický dojem. Jeho praktické využitie a moderný vzhľad ho robia ideálnym doplnkom každého priestoru.';

        $armchairs = [
            [
                'title' => 'Moderné kreslo',
                'price' => 149.99,
                'color_id' => $colorBeigeId,
                'category_id' => $categoryArmchairId,
                'material_id' => $materialTextilId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'armchair-01.png', 'is_main' => true],
                    ['file' => 'armchair-01-1.png', 'is_main' => false],
                    ['file' => 'armchair-01-2.png', 'is_main' => false],
                    ['file' => 'armchair-01-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 80, 'length' => 90, 'depth' => 85],
            ],
            [
                'title' => 'Kožené kreslo',
                'price' => 169.99,
                'color_id' => $colorWhiteId,
                'category_id' => $categoryArmchairId,
                'material_id' => $materialLeatherId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'armchair-02.png', 'is_main' => true],
                    ['file' => 'armchair-02-1.png', 'is_main' => false],
                    ['file' => 'armchair-02-2.png', 'is_main' => false],
                    ['file' => 'armchair-02-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 85, 'length' => 95, 'depth' => 90],
            ],
            [
                'title' => 'Elegantné kreslo',
                'price' => 179.99,
                'color_id' => $colorYellowId,
                'category_id' => $categoryArmchairId,
                'material_id' => $materialLeatherId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'armchair-03.png', 'is_main' => true],
                    ['file' => 'armchair-03-1.png', 'is_main' => false],
                    ['file' => 'armchair-03-2.png', 'is_main' => false],
                    ['file' => 'armchair-03-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 80, 'length' => 100, 'depth' => 85],
            ],
            [
                'title' => 'Pohodlné kreslo',
                'price' => 189.99,
                'color_id' => $colorBeigeId,
                'category_id' => $categoryArmchairId,
                'material_id' => $materialTextilId,
                'placement_id' => $placementBedroomId,
                'images' => [
                    ['file' => 'armchair-04.png', 'is_main' => true],
                    ['file' => 'armchair-04-1.png', 'is_main' => false],
                    ['file' => 'armchair-04-2.png', 'is_main' => false],
                    ['file' => 'armchair-04-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 85, 'length' => 95, 'depth' => 90],
            ],
        ];

        foreach ($armchairs as $data) {
            $productId = DB::table('products')->insertGetId([
                'title' => $data['title'],
                'description' => $productDescription,
                'color_id' => $data['color_id'],
                'category_id' => $data['category_id'],
                'material_id' => $data['material_id'],
                'placement_id' => $data['placement_id'],
                'price' => $data['price'],
                'in_stock' => 20,
                'valid' => true,
                'width' => $data['dimensions']['width'],
                'length' => $data['dimensions']['length'],
                'depth' => $data['dimensions']['depth'],
                'added_date' => now(),
                'code' => strtoupper(Str::random(10)),
            ]);

            foreach ($data['images'] as $image) {
                DB::table('image_references')->insert([
                    'product_id' => $productId,
                    'title' => $image['is_main'] ? 'Hlavný obrázok - ' . $image['file'] : 'Detailný obrázok - ' . $image['file'],
                    'path' => 'products/' . $image['file'],
                    'is_main' => $image['is_main'],
                ]);
            }
        }

        $beds = [
            [
                'title' => 'Klasická posteľ',
                'price' => 499.99,
                'color_id' => $colorBrownId,
                'category_id' => $categoryBedId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementBedroomId,
                'images' => [
                    ['file' => 'bed-01.png', 'is_main' => true],
                    ['file' => 'bed-01-1.png', 'is_main' => false],
                    ['file' => 'bed-01-2.png', 'is_main' => false],
                    ['file' => 'bed-01-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 160, 'length' => 200, 'depth' => 45],
            ],
            [
                'title' => 'Moderná posteľ',
                'price' => 649.99,
                'color_id' => $colorGreyId,
                'category_id' => $categoryBedId,
                'material_id' => $materialTextilId,
                'placement_id' => $placementBedroomId,
                'images' => [
                    ['file' => 'bed-02.png', 'is_main' => true],
                    ['file' => 'bed-02-1.png', 'is_main' => false],
                    ['file' => 'bed-02-2.png', 'is_main' => false],
                    ['file' => 'bed-02-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 180, 'length' => 210, 'depth' => 50],
            ],
            [
                'title' => 'Manželská posteľ',
                'price' => 399.99,
                'color_id' => $colorBrownId,
                'category_id' => $categoryBedId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementBedroomId,
                'images' => [
                    ['file' => 'bed-03.png', 'is_main' => true],
                    ['file' => 'bed-03-1.png', 'is_main' => false],
                    ['file' => 'bed-03-2.png', 'is_main' => false],
                    ['file' => 'bed-03-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 200, 'length' => 220, 'depth' => 55],
            ],
            [
                'title' => 'Súčasná posteľ',
                'price' => 549.99,
                'color_id' => $colorWhiteId,
                'category_id' => $categoryBedId,
                'material_id' => $materialTextilId,
                'placement_id' => $placementBedroomId,
                'images' => [
                    ['file' => 'bed-04.png', 'is_main' => true],
                    ['file' => 'bed-04-1.png', 'is_main' => false],
                    ['file' => 'bed-04-2.png', 'is_main' => false],
                    ['file' => 'bed-04-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 190, 'length' => 210, 'depth' => 50],
            ],
        ];
        
        foreach ($beds as $data) {
            $productId = DB::table('products')->insertGetId([
                'title' => $data['title'],
                'description' => $productDescription,
                'color_id' => $data['color_id'],
                'category_id' => $data['category_id'],
                'material_id' => $data['material_id'],
                'placement_id' => $data['placement_id'],
                'price' => $data['price'],
                'in_stock' => 20,
                'valid' => true,
                'width' => $data['dimensions']['width'],
                'length' => $data['dimensions']['length'],
                'depth' => $data['dimensions']['depth'],
                'added_date' => now(),
                'code' => strtoupper(Str::random(10)),
            ]);
        
            foreach ($data['images'] as $image) {
                DB::table('image_references')->insert([
                    'product_id' => $productId,
                    'title' => $image['is_main'] ? 'Hlavný obrázok - ' . $image['file'] : 'Detailný obrázok - ' . $image['file'],
                    'path' => 'products/' . $image['file'],
                    'is_main' => $image['is_main'],
                ]);
            }
        }

        $chairs = [
            [
                'title' => 'Klasická stolička',
                'price' => 29.99,
                'color_id' => $colorBrownId,
                'category_id' => $categoryChairId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementKitchenId,
                'images' => [
                    ['file' => 'chair-01.png', 'is_main' => true],
                    ['file' => 'chair-01-1.png', 'is_main' => false],
                    ['file' => 'chair-01-2.png', 'is_main' => false],
                    ['file' => 'chair-01-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 50, 'length' => 50, 'depth' => 90],
            ],
            [
                'title' => 'Herná stolička',
                'price' => 129.99,
                'color_id' => $colorRedId,
                'category_id' => $categoryChairId,
                'material_id' => $materialLeatherId,
                'placement_id' => $placementOfficeId,
                'images' => [
                    ['file' => 'chair-02.png', 'is_main' => true],
                    ['file' => 'chair-02-1.png', 'is_main' => false],
                    ['file' => 'chair-02-2.png', 'is_main' => false],
                    ['file' => 'chair-02-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 55, 'length' => 55, 'depth' => 95],
            ],
            [
                'title' => 'Ergonomická stolička',
                'price' => 199.99,
                'color_id' => $colorBlackId,
                'category_id' => $categoryChairId,
                'material_id' => $materialLeatherId,
                'placement_id' => $placementOfficeId,
                'images' => [
                    ['file' => 'chair-03.png', 'is_main' => true],
                    ['file' => 'chair-03-1.png', 'is_main' => false],
                    ['file' => 'chair-03-2.png', 'is_main' => false],
                    ['file' => 'chair-03-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 60, 'length' => 60, 'depth' => 100],
            ],
            [
                'title' => 'Bežná Stolička',
                'price' => 59.99,
                'color_id' => $colorBlackId,
                'category_id' => $categoryChairId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'chair-04.png', 'is_main' => true],
                    ['file' => 'chair-04-1.png', 'is_main' => false],
                    ['file' => 'chair-04-2.png', 'is_main' => false],
                    ['file' => 'chair-04-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 45, 'length' => 50, 'depth' => 80],
            ],
        ];
        
        foreach ($chairs as $data) {
            $productId = DB::table('products')->insertGetId([
                'title' => $data['title'],
                'description' => $productDescription,
                'color_id' => $data['color_id'],
                'category_id' => $data['category_id'],
                'material_id' => $data['material_id'],
                'placement_id' => $data['placement_id'],
                'price' => $data['price'],
                'in_stock' => 20,
                'valid' => true,
                'width' => $data['dimensions']['width'],
                'length' => $data['dimensions']['length'],
                'depth' => $data['dimensions']['depth'],
                'added_date' => now(),
                'code' => strtoupper(Str::random(10)),
            ]);
        
            foreach ($data['images'] as $image) {
                DB::table('image_references')->insert([
                    'product_id' => $productId,
                    'title' => $image['is_main'] ? 'Hlavný obrázok - ' . $image['file'] : 'Detailný obrázok - ' . $image['file'],
                    'path' => 'products/' . $image['file'],
                    'is_main' => $image['is_main'],
                ]);
            }
        }

        $sofas = [
            [
                'title' => 'Textilná pohovka',
                'price' => 349.99,
                'color_id' => $colorWhiteId,
                'category_id' => $categorySofaId,
                'material_id' => $materialTextilId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'sofa-01.png', 'is_main' => true],
                    ['file' => 'sofa-01-1.png', 'is_main' => false],
                    ['file' => 'sofa-01-2.png', 'is_main' => false],
                    ['file' => 'sofa-01-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 200, 'length' => 90, 'depth' => 85],
            ],
            [
                'title' => 'Moderná pohovka',
                'price' => 429.00,
                'color_id' => $colorRedId,
                'category_id' => $categorySofaId,
                'material_id' => $materialLeatherId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'sofa-02.png', 'is_main' => true],
                    ['file' => 'sofa-02-1.png', 'is_main' => false],
                    ['file' => 'sofa-02-2.png', 'is_main' => false],
                    ['file' => 'sofa-02-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 220, 'length' => 100, 'depth' => 90],
            ],
            [
                'title' => 'Semišová sedačka',
                'price' => 399.99,
                'color_id' => $colorGreenId,
                'category_id' => $categorySofaId,
                'material_id' => $materialTextilId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'sofa-03.png', 'is_main' => true],
                    ['file' => 'sofa-03-1.png', 'is_main' => false],
                    ['file' => 'sofa-03-2.png', 'is_main' => false],
                    ['file' => 'sofa-03-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 230, 'length' => 105, 'depth' => 95],
            ],
            [
                'title' => 'Pohovka k TV',
                'price' => 649.50,
                'color_id' => $colorBeigeId,
                'category_id' => $categorySofaId,
                'material_id' => $materialTextilId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'sofa-04.png', 'is_main' => true],
                    ['file' => 'sofa-04-1.png', 'is_main' => false],
                    ['file' => 'sofa-04-2.png', 'is_main' => false],
                    ['file' => 'sofa-04-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 250, 'length' => 130, 'depth' => 90],
            ],
        ];
        
        foreach ($sofas as $data) {
            $productId = DB::table('products')->insertGetId([
                'title' => $data['title'],
                'description' => $productDescription,
                'color_id' => $data['color_id'],
                'category_id' => $data['category_id'],
                'material_id' => $data['material_id'],
                'placement_id' => $data['placement_id'],
                'price' => $data['price'],
                'in_stock' => 20,
                'valid' => true,
                'width' => $data['dimensions']['width'],
                'length' => $data['dimensions']['length'],
                'depth' => $data['dimensions']['depth'],
                'added_date' => now(),
                'code' => strtoupper(Str::random(10)),
            ]);
        
            foreach ($data['images'] as $image) {
                DB::table('image_references')->insert([
                    'product_id' => $productId,
                    'title' => $image['is_main'] ? 'Hlavný obrázok - ' . $image['file'] : 'Detailný obrázok - ' . $image['file'],
                    'path' => 'products/' . $image['file'],
                    'is_main' => $image['is_main'],
                ]);
            }
        }

        $tables = [
            [
                'title' => 'Dekoračný stôl',
                'price' => 219.90,
                'color_id' => $colorGreyId,
                'category_id' => $categoryTableId,
                'material_id' => $materialMetalId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'table-01.png', 'is_main' => true],
                    ['file' => 'table-01-1.png', 'is_main' => false],
                    ['file' => 'table-01-2.png', 'is_main' => false],
                    ['file' => 'table-01-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 130, 'length' => 90, 'depth' => 130],
            ],
            [
                'title' => 'Záhradný stôl',
                'price' => 89.50,
                'color_id' => $colorBrownId,
                'category_id' => $categoryTableId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementExteriorId,
                'images' => [
                    ['file' => 'table-02.png', 'is_main' => true],
                    ['file' => 'table-02-1.png', 'is_main' => false],
                    ['file' => 'table-02-2.png', 'is_main' => false],
                    ['file' => 'table-02-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 110, 'length' => 60, 'depth' => 45],
            ],
            [
                'title' => 'Kuchynský stôl',
                'price' => 139.99,
                'color_id' => $colorWhiteId,
                'category_id' => $categoryTableId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementKitchenId,
                'images' => [
                    ['file' => 'table-03.png', 'is_main' => true],
                    ['file' => 'table-03-1.png', 'is_main' => false],
                    ['file' => 'table-03-2.png', 'is_main' => false],
                    ['file' => 'table-03-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 140, 'length' => 70, 'depth' => 75],
            ],
            [
                'title' => 'Stôl z masívu',
                'price' => 359.00,
                'color_id' => $colorBrownId,
                'category_id' => $categoryTableId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'table-04.png', 'is_main' => true],
                    ['file' => 'table-04-1.png', 'is_main' => false],
                    ['file' => 'table-04-2.png', 'is_main' => false],
                    ['file' => 'table-04-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 180, 'length' => 80, 'depth' => 100],
            ],
        ];
        
        foreach ($tables as $data) {
            $productId = DB::table('products')->insertGetId([
                'title' => $data['title'],
                'description' => $productDescription,
                'color_id' => $data['color_id'],
                'category_id' => $data['category_id'],
                'material_id' => $data['material_id'],
                'placement_id' => $data['placement_id'],
                'price' => $data['price'],
                'in_stock' => 20,
                'valid' => true,
                'width' => $data['dimensions']['width'],
                'length' => $data['dimensions']['length'],
                'depth' => $data['dimensions']['depth'],
                'added_date' => now(),
                'code' => strtoupper(Str::random(10)),
            ]);
        
            foreach ($data['images'] as $image) {
                DB::table('image_references')->insert([
                    'product_id' => $productId,
                    'title' => $image['is_main'] ? 'Hlavný obrázok - ' . $image['file'] : 'Detailný obrázok - ' . $image['file'],
                    'path' => 'products/' . $image['file'],
                    'is_main' => $image['is_main'],
                ]);
            }
        }

        $wardrobes = [
            [
                'title' => 'Šatníková skriňa',
                'price' => 179.90,
                'color_id' => $colorWhiteId,
                'category_id' => $categoryWardrobeId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementBedroomId,
                'images' => [
                    ['file' => 'wardrobe-01.png', 'is_main' => true],
                    ['file' => 'wardrobe-01-1.png', 'is_main' => false],
                    ['file' => 'wardrobe-01-2.png', 'is_main' => false],
                    ['file' => 'wardrobe-01-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 180, 'length' => 60, 'depth' => 200],
            ],
            [
                'title' => 'Drevená skriňa',
                'price' => 459.00,
                'color_id' => $colorBrownId,
                'category_id' => $categoryWardrobeId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementLivingRoomId,
                'images' => [
                    ['file' => 'wardrobe-02.png', 'is_main' => true],
                    ['file' => 'wardrobe-02-1.png', 'is_main' => false],
                    ['file' => 'wardrobe-02-2.png', 'is_main' => false],
                    ['file' => 'wardrobe-02-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 190, 'length' => 65, 'depth' => 210],
            ],
            [
                'title' => 'Presklená skriňa',
                'price' => 499.99,
                'color_id' => $colorBeigeId,
                'category_id' => $categoryWardrobeId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementBedroomId,
                'images' => [
                    ['file' => 'wardrobe-03.png', 'is_main' => true],
                    ['file' => 'wardrobe-03-1.png', 'is_main' => false],
                    ['file' => 'wardrobe-03-2.png', 'is_main' => false],
                    ['file' => 'wardrobe-03-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 200, 'length' => 70, 'depth' => 220],
            ],
            [
                'title' => 'Elegantná skriňa',
                'price' => 229.90,
                'color_id' => $colorBrownId,
                'category_id' => $categoryWardrobeId,
                'material_id' => $materialWoodId,
                'placement_id' => $placementBedroomId,
                'images' => [
                    ['file' => 'wardrobe-04.png', 'is_main' => true],
                    ['file' => 'wardrobe-04-1.png', 'is_main' => false],
                    ['file' => 'wardrobe-04-2.png', 'is_main' => false],
                    ['file' => 'wardrobe-04-3.png', 'is_main' => false],
                ],
                'dimensions' => ['width' => 185, 'length' => 60, 'depth' => 205],
            ],
        ];
        
        foreach ($wardrobes as $data) {
            $productId = DB::table('products')->insertGetId([
                'title' => $data['title'],
                'description' => $productDescription,
                'color_id' => $data['color_id'],
                'category_id' => $data['category_id'],
                'material_id' => $data['material_id'],
                'placement_id' => $data['placement_id'],
                'price' => $data['price'],
                'in_stock' => 20,
                'valid' => true,
                'width' => $data['dimensions']['width'],
                'length' => $data['dimensions']['length'],
                'depth' => $data['dimensions']['depth'],
                'added_date' => now(),
                'code' => strtoupper(Str::random(10)),
            ]);
        
            foreach ($data['images'] as $image) {
                DB::table('image_references')->insert([
                    'product_id' => $productId,
                    'title' => $image['is_main'] ? 'Hlavný obrázok - ' . $image['file'] : 'Detailný obrázok - ' . $image['file'],
                    'path' => 'products/' . $image['file'],
                    'is_main' => $image['is_main'],
                ]);
            }
        }        
    }
}
