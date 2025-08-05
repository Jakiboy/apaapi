<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

use PHPUnit\Framework\TestCase;
use Apaapi\includes\{NormalizationService, NormalizerConfig, Normalizer};

/**
 * Test normalization service functionality.
 */
class NormalizationServiceTest extends TestCase
{
    private NormalizationService $normalizer;
    
    protected function setUp(): void
    {
        $this->normalizer = new NormalizationService(new NormalizerConfig());
    }
    
    /** @test */
    public function testNormalizeItemDataCorrectly(): void
    {
        $rawItem = [
            'ASIN' => 'B00TEST123',
            'ItemInfo' => [
                'Title' => [
                    'DisplayValue' => 'Test Product'
                ]
            ],
            'DetailPageURL' => 'https://amazon.com/test'
        ];
        
        $result = $this->normalizer->normalize([$rawItem], 'GetItems');
        
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('asin', $result[0]);
        $this->assertEquals('B00TEST123', $result[0]['asin']);
    }
    
    /** @test */
    public function testHandlesMissingFieldsGracefully(): void
    {
        $incompleteItem = ['ASIN' => 'B00TEST'];
        
        $result = $this->normalizer->normalize([$incompleteItem], 'GetItems');
        
        $this->assertArrayHasKey('asin', $result[0]);
        $this->assertEquals('B00TEST', $result[0]['asin']);
        $this->assertEquals(0, $result[0]['price']);
    }
    
    /** @test */
    public function testFormatsIdsCorrectly(): void
    {
        $id = ' b00test123 ';
        $result = $this->normalizer->formatId($id);
        
        $this->assertEquals('B00TEST123', $result);
    }
    
    /** @test */
    public function testFormatsMultipleIds(): void
    {
        $ids = 'b00test1, b00test2, b00test3';
        $result = $this->normalizer->formatIds($ids);
        
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertEquals('B00TEST1', $result[0]);
        $this->assertEquals('B00TEST2', $result[1]);
        $this->assertEquals('B00TEST3', $result[2]);
    }
    
    /** @test */
    public function testFormatsKeywords(): void
    {
        $keywords = ['sony', 'xperia', 'smartphone'];
        $result = $this->normalizer->formatKeywords($keywords);
        
        $this->assertIsString($result);
        $this->assertEquals('sony, xperia, smartphone', $result);
    }
    
    /** @test */
    public function testRespectsConfiguration(): void
    {
        $config = new NormalizerConfig(limit: 3, format: false);
        $normalizer = new NormalizationService($config);
        
        $this->assertEquals(3, $normalizer->getConfig()->limit);
        $this->assertFalse($normalizer->getConfig()->format);
    }
}
