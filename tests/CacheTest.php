<?php

use PHPUnit\Framework\TestCase;
use Apaapi\includes\Cache;
use Apaapi\interfaces\RequestInterface;

class CacheTest extends TestCase
{
    public function testSetAndGetTtl()
    {
        $ttl = 7200;
        Cache::setTtl($ttl);
        $this->assertEquals($ttl, Cache::getTtl());
    }

    public function testSetAndGetSalt()
    {
        $salt = 'newSalt';
        Cache::setSalt($salt);
        $this->assertEquals($salt, Cache::getSalt());
    }

    public function testSetAndGetExt()
    {
        $ext = 'newExt';
        Cache::setExt($ext);
        $this->assertEquals($ext, Cache::getExt());
    }

    public function testGetKey()
    {
        $request = $this->createMock(RequestInterface::class);
        $request->method('getParams')
                ->willReturn(['http' => ['content' => 'testContent']]);

        $key = Cache::getKey($request);
        $this->assertIsString($key);
    }

    public function testGenerateKey()
    {
        $item = 'testItem';
        $key = Cache::generateKey($item);
        $this->assertIsString($key);
    }

    public function testSetAndGet()
    {
        $key = 'testKey';
        $value = 'testValue';

        Cache::set($key, $value);
        $this->assertEquals($value, Cache::get($key));
    }
}