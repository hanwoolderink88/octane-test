<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\HasData;

class CatalogSeeder extends Seeder
{
    use HasData;

    public function run(): void
    {
        $this->seedAttributes();
        $this->seedCategoriesAndProducts();
    }

    public function seedAttributes(): void
    {
        $attributes = $this->getData('attributes');

        foreach ($attributes as $attributeData) {
            $attribute = $this->createAttribute($attributeData);

            foreach ($attributeData['values'] as $value) {
                $this->createAttributeValue($attribute, $value);
            }
        }
    }

    public function seedCategoriesAndProducts(): void
    {
        $books = $this->getData('books');
        $jeans = $this->getData('jeans');

        $catalog = [...$books, ...$jeans];

        foreach ($catalog as $categoryData) {
            $this->createCategory($categoryData, null);
        }
    }

    public function createAttribute(array $attributeData): Attribute
    {
        $found = Attribute::where('name', $attributeData['name'])->first();

        if ($found) {
            return $found;
        }

        return Attribute::factory()->create([
            'name' => $attributeData['name'],
        ]);
    }

    public function createAttributeValue(Attribute $attribute, string $value): AttributeValue
    {
        $found = AttributeValue::where('value', $value)->first();

        if ($found) {
            return $found;
        }

        return AttributeValue::factory()->create([
            'value' => $value,
            'attribute_id' => $attribute->id,
        ]);
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

        $attributes = $productData['attributes'] ?? [];

        foreach ($attributes as $attributeName => $attributeValue) {
            $attribute = Attribute::query()
                ->where('name', $attributeName)
                ->first();

            if (!$attribute) {
                continue;
            }

            $value = AttributeValue::query()
                ->whereRelation('attribute', 'name', $attribute->name)
                ->where('value', $attributeValue)->first();

            if (!$value) {
                continue;
            }

            $product->attributeValues()->syncWithoutDetaching($value);
        }
    }
}
