<?php

declare(strict_types=1);

namespace Test\Cliffordvickrey\DogsPlayingPoker\Config;

use Cliffordvickrey\DogsPlayingPoker\Config\ConfigProvider;
use PHPStan\Testing\TestCase;
use function is_array;
use function is_bool;

class ConfigProviderTest extends TestCase
{
    public function testInvoke(): void
    {
        $config = (new ConfigProvider())();

        $this->assertArrayHasKey(ConfigProvider::class, $config);
        $this->assertIsArray($config[ConfigProvider::class]);

        $scopedConfig = $config[ConfigProvider::class];
        
        $this->assertArrayHasKey('cardsFileName', $scopedConfig);
        $this->assertIsString($scopedConfig['cardsFileName']);
        $this->assertFileExists($scopedConfig['cardsFileName']);

        $this->assertArrayHasKey('dogsPlayingPokerFileName', $scopedConfig);
        $this->assertIsString($scopedConfig['dogsPlayingPokerFileName']);
        $this->assertFileExists($scopedConfig['dogsPlayingPokerFileName']);

        $keys = ['width', 'height', 'x', 'y'];
        $this->assertArrayHasKey('cardSource', $scopedConfig);
        $this->assertIsArray($scopedConfig['cardSource']);
        $this->assertCount(52, $scopedConfig['cardSource']);

        foreach ($scopedConfig['cardSource'] as $cardConfig) {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $cardConfig);
                $this->assertIsInt($cardConfig[$key]);
            }
        }

        $distortionKeys = ['topLeft', 'topRight', 'bottomRight', 'bottomLeft'];
        $this->assertArrayHasKey('cardDestination', $scopedConfig);
        $this->assertIsArray($scopedConfig['cardDestination']);
        $this->assertNotEmpty($scopedConfig['cardDestination']);

        foreach ($scopedConfig['cardDestination'] as $cardDestination) {
            $this->assertArrayHasKey('x', $cardDestination);
            $this->assertIsInt($cardDestination['x']);

            $this->assertArrayHasKey('y', $cardDestination);
            $this->assertIsInt($cardDestination['y']);

            $this->assertArrayHasKey('scale', $cardDestination);
            $this->assertIsFloat($cardDestination['scale']);

            $flip = $cardDestination['flip'] ?? null;
            $this->assertTrue(null === $flip || is_bool($flip));

            $this->assertArrayHasKey('distortion', $cardDestination);
            $this->assertIsArray($cardDestination['distortion']);

            foreach ($distortionKeys as $distortionKey) {
                $this->assertArrayHasKey($distortionKey, $cardDestination['distortion']);
                $this->assertIsArray($cardDestination['distortion'][$distortionKey]);
                $distortion = $cardDestination['distortion'][$distortionKey];
                $this->assertArrayHasKey('x', $distortion);
                $this->assertIsInt($distortion['x']);
                $this->assertArrayHasKey('y', $distortion);
                $this->assertIsInt($distortion['y']);
            }

            $mask = $cardDestination['mask'] ?? null;
            $this->assertTrue(null === $mask || is_array($mask));
            if (is_array($mask)) {
                foreach ($mask as $point) {
                    $this->assertArrayHasKey('x', $point);
                    $this->assertIsInt($point['x']);
                    $this->assertArrayHasKey('y', $point);
                    $this->assertIsInt($point['y']);
                }
            }
        }
    }
}
