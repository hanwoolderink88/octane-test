<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\Traits\HasData;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    use HasData;

    public function run(): void
    {
        $books = $this->getData('books');

        $catalog = [...$books];

        foreach ($catalog as $categoryData) {
            $this->createCategory($categoryData, null);
        }
    }

    public function createCategory(array $categoryData, ?Category $parent): void
    {
        $name = $categoryData['name'];
        $subCategories = $categoryData['categories'] ?? null;
        $products = $categoryData['products'] ?? null;

        $found = Category::where('name', $name)->first();

        if ($found) {
            $category = $found;
        } else {
            $category = new Category();
            $category->name = $name;
            $category->save();
        }

        if ($parent) {
            $category->parent()->associate($parent);
            $category->save();
        }

        if ($products) {
            foreach ($products as $productData) {
                $this->createProduct($productData, $category);
            }
        }

        if ($subCategories) {
            foreach ($subCategories as $subCategoryData) {
                $this->createCategory($subCategoryData, $category);
            }
        }
    }

    public function createProduct(array $productData, Category $category): void
    {
        /** @var Product|null $found */
        $found = Product::where('name', $productData['name'])->first();

        if ($found) {
            $found->categories()->syncWithoutDetaching($category);

            return;
        }

        $product = new Product();
        $product->name = $productData['name'];;
        $product->price = $productData['price'];
        $product->save();

        $product->categories()->syncWithoutDetaching($category);
    }
}
