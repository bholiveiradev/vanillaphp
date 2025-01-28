<?php

declare(strict_types=1);

namespace Unit\App\Models;

use App\Models\Product;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ProductTest extends TestCase
{
    private Product $model;

    protected function setUp(): void
    {
        $this->model = new Product();
    }

    /**
     * @test
     */
    public function propertyTableMustHaveCorrectValue()
    {
        // Arrange
        $reflectionClass = new ReflectionClass($this->model);
        $reflectionProperty = $reflectionClass->getProperty('table');
        $reflectionProperty->setAccessible(true);

        // Act
        $table = $reflectionProperty->getValue($this->model);

        // Arrange
        $this->assertEquals('products', $table);
    }

    /**
     * @test
     */
    public function propertyAttributesMustHaveCorrectArrayValues()
    {
        // Arrange
        $reflectionClass = new ReflectionClass($this->model);
        $reflectionProperty = $reflectionClass->getProperty('attributes');
        $reflectionProperty->setAccessible(true);

        // Act
        $attributes = $reflectionProperty->getValue($this->model);

        // Assert
        $this->assertEquals(['name', 'price', 'stock'], $attributes);
    }
}
