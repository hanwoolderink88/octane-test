<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Models\Stock;
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
        $shirts = $this->getData('shirts');

        $catalog = [...$shirts];

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

        foreach ($attributes as $attributeName => $attributeValues) {
            foreach ($attributeValues as $attributeValue) {
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

                $productAttribute = new ProductAttribute();
                $productAttribute->attribute()->associate($attribute);
                $productAttribute->value()->associate($value);

                $product->attributeValues()->save($productAttribute);
            }
        }

        $stockData = $productData['stock'] ?? null;

        if (is_numeric($stockData)) {
            // If product has no attributes (variations) create one stock row for it
            $stock = new Stock();
            $stock->quantity = $stockData;
            $stock->product()->associate($product);
            $stock->save();
        } elseif (is_array($stockData)) {
            // If product has attributes (variations) create stock rows for each variation
            foreach ($stockData as $variationData) {
                $stock = new Stock();
                $stock->quantity = $variationData['quantity'];

                $stock->product()->associate($product);

                $attributeIds = [];

                foreach ($variationData['attributes'] as $attributeName => $attributeValue) {
                    $attributeIds[] = $product->attributeValues()
                        ->whereRelation('attribute', 'name', $attributeName)
                        ->whereRelation('value', 'value', $attributeValue)
                        ->first(['id'])
                        ->id;
                }

                $stock->save();

                $stock->attributeValues()->sync($attributeIds);
            }
        }
    }
}
