<?php
declare(strict_types=1);

namespace Panth\PerformanceOptimizer\Test\Unit\Service;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Panth\PerformanceOptimizer\Service\ImageDimensionRegistry;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ImageDimensionRegistryTest extends TestCase
{
    private string $pub;

    /** @var string[] */
    private array $created = [];

    protected function setUp(): void
    {
        $this->pub = sys_get_temp_dir() . '/panth_perf_pub_' . uniqid('', true);
        mkdir($this->pub, 0777, true);
    }

    protected function tearDown(): void
    {
        foreach (array_reverse($this->created) as $path) {
            if (is_file($path)) {
                unlink($path);
            } elseif (is_dir($path)) {
                rmdir($path);
            }
        }
        if (is_dir($this->pub)) {
            rmdir($this->pub);
        }
    }

    private function png(string $relative, int $width, int $height): void
    {
        $this->write(
            $relative,
            "\x89PNG\r\n\x1a\n" . pack('N', 13) . 'IHDR' . pack('NN', $width, $height)
            . "\x08\x02\x00\x00\x00" . pack('N', 0)
        );
    }

    private function write(string $relative, string $content): void
    {
        $parts = explode('/', $relative);
        array_pop($parts);
        $dir = $this->pub;
        foreach ($parts as $part) {
            $dir .= '/' . $part;
            if (!is_dir($dir)) {
                mkdir($dir);
                $this->created[] = $dir;
            }
        }
        file_put_contents($this->pub . '/' . $relative, $content);
        $this->created[] = $this->pub . '/' . $relative;
    }

    private function filesystem(): Filesystem
    {
        $pub = $this->pub;
        $read = $this->createStub(ReadInterface::class);
        $read->method('getAbsolutePath')->willReturnCallback(
            static fn($path = null) => $pub . '/' . (string) $path
        );
        $filesystem = $this->createStub(Filesystem::class);
        $filesystem->method('getDirectoryRead')->willReturnCallback(
            static function (string $code) use ($read) {
                if ($code !== DirectoryList::PUB) {
                    throw new \InvalidArgumentException('unexpected directory ' . $code);
                }
                return $read;
            }
        );
        return $filesystem;
    }

    private function storeManager(string $baseUrl = 'https://shop.example.com/'): StoreManagerInterface
    {
        $store = $this->createStub(Store::class);
        $store->method('getBaseUrl')->willReturn($baseUrl);
        $storeManager = $this->createStub(StoreManagerInterface::class);
        $storeManager->method('getStore')->willReturn($store);
        return $storeManager;
    }

    private function cache(): CacheInterface
    {
        $cache = $this->createStub(CacheInterface::class);
        $cache->method('load')->willReturn(false);
        return $cache;
    }

    private function registry(
        ?CacheInterface $cache = null,
        ?Filesystem $filesystem = null,
        ?StoreManagerInterface $storeManager = null
    ): ImageDimensionRegistry {
        return new ImageDimensionRegistry(
            $cache ?? $this->cache(),
            $filesystem ?? $this->filesystem(),
            $storeManager ?? $this->storeManager(),
            new Json()
        );
    }

    public function testReadsDimensionsFromPubDirectory(): void
    {
        $this->png('media/catalog/a.png', 640, 480);

        $this->assertSame(
            ['width' => 640, 'height' => 480],
            $this->registry()->getDimensions('/media/catalog/a.png')
        );
    }

    public function testQueryStringAndFragmentAreStripped(): void
    {
        $this->png('media/a.png', 10, 20);
        $registry = $this->registry();

        $this->assertSame(['width' => 10, 'height' => 20], $registry->getDimensions(' /media/a.png?v=3#top '));
        $this->assertSame(['width' => 10, 'height' => 20], $registry->getDimensions('/media/a.png#frag'));
    }

    public function testStaticVersionSegmentIsRemoved(): void
    {
        $this->png('static/frontend/logo.png', 120, 40);

        $this->assertSame(
            ['width' => 120, 'height' => 40],
            $this->registry()->getDimensions('/static/version1712345678/frontend/logo.png')
        );
    }

    public function testPercentEncodedPathIsDecoded(): void
    {
        $this->png('media/my file.png', 3, 4);

        $this->assertSame(['width' => 3, 'height' => 4], $this->registry()->getDimensions('/media/my%20file.png'));
    }

    public function testAbsoluteUrlOnStoreHostIsResolvedCaseInsensitively(): void
    {
        $this->png('media/a.png', 7, 9);

        $this->assertSame(
            ['width' => 7, 'height' => 9],
            $this->registry()->getDimensions('https://SHOP.example.com/media/a.png')
        );
    }

    public function testAbsoluteUrlWithoutPathResolvesToRootAndMisses(): void
    {
        $this->assertNull($this->registry()->getDimensions('http://shop.example.com'));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function rejectedSourceProvider(): array
    {
        return [
            'empty' => [''],
            'whitespace' => ['   '],
            'data uri' => ['data:image/png;base64,AAAA'],
            'query only' => ['?v=1'],
            'relative path' => ['media/a.png'],
            'foreign host' => ['https://cdn.example.com/media/a.png'],
            'parent traversal' => ['/media/../app/etc/env.php'],
            'encoded traversal' => ['/media/%2e%2e/secret.png'],
            'trailing dot segment' => ['/media/.'],
            'nul byte' => ['/media/a.png%00.jpg'],
        ];
    }

    #[DataProvider('rejectedSourceProvider')]
    public function testUnsafeOrForeignSourcesAreRejectedWithoutCacheAccess(string $src): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->never())->method('load');
        $cache->expects($this->never())->method('save');

        $this->assertNull($this->registry($cache)->getDimensions($src));
    }

    public function testProtocolRelativeForeignUrlIsTreatedAsRemote(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->never())->method('load');
        $cache->expects($this->never())->method('save');

        $this->assertNull($this->registry($cache)->getDimensions('//cdn.example.com/a.png'));
    }

    public function testProtocolRelativeUrlOnStoreHostIsResolved(): void
    {
        $this->png('media/a.png', 5, 6);

        $this->assertSame(
            ['width' => 5, 'height' => 6],
            $this->registry()->getDimensions('//shop.example.com/media/a.png')
        );
    }

    public function testAbsoluteUrlRejectedWhenStoreLookupFails(): void
    {
        $storeManager = $this->createStub(StoreManagerInterface::class);
        $storeManager->method('getStore')->willThrowException(new \RuntimeException('no store'));
        $this->png('media/a.png', 7, 9);

        $registry = $this->registry(null, null, $storeManager);

        $this->assertNull($registry->getDimensions('https://shop.example.com/media/a.png'));
        $this->assertSame(['width' => 7, 'height' => 9], $registry->getDimensions('/media/a.png'));
    }

    public function testMissingDirectoryAndNonImageFilesYieldNull(): void
    {
        $this->write('media/notes.txt', 'plain text');
        $registry = $this->registry();

        $this->assertNull($registry->getDimensions('/media/missing.png'));
        $this->assertNull($registry->getDimensions('/media/notes.txt'));
        $this->assertNull($registry->getDimensions('/media'));
    }

    public function testFilesystemFailureYieldsNull(): void
    {
        $filesystem = $this->createStub(Filesystem::class);
        $filesystem->method('getDirectoryRead')->willThrowException(new \RuntimeException('fs down'));

        $this->assertNull($this->registry(null, $filesystem)->getDimensions('/media/a.png'));
    }

    public function testResultIsCachedWithTagAndLifetime(): void
    {
        $this->png('media/a.png', 5, 6);
        $expectedKey = 'panth_perf_imgdim_' . hash('sha256', '/media/a.png');

        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('load')->with($expectedKey)->willReturn(false);
        $cache->expects($this->once())
            ->method('save')
            ->with('{"width":5,"height":6}', $expectedKey, ['panth_perf_imgdim'], 86400);

        $this->assertSame(
            ['width' => 5, 'height' => 6],
            $this->registry($cache)->getDimensions('/media/a.png?cache=bust')
        );
    }

    public function testMissesAreCachedAsNull(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('load')->willReturn(false);
        $cache->expects($this->once())->method('save')->with('null');

        $this->assertNull($this->registry($cache)->getDimensions('/media/nothing.png'));
    }

    public function testRepeatedLookupsAreMemoized(): void
    {
        $this->png('media/a.png', 5, 6);
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('load')->willReturn(false);
        $cache->expects($this->once())->method('save');
        $registry = $this->registry($cache);

        $first = $registry->getDimensions('/media/a.png');
        $second = $registry->getDimensions('/media/a.png?v=2');

        $this->assertSame(['width' => 5, 'height' => 6], $first);
        $this->assertSame($first, $second);
    }

    public function testCachedValueIsUsedWithoutTouchingDisk(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('load')->willReturn('{"width":11,"height":22}');
        $cache->expects($this->never())->method('save');
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->expects($this->never())->method('getDirectoryRead');

        $this->assertSame(
            ['width' => 11, 'height' => 22],
            $this->registry($cache, $filesystem)->getDimensions('/media/a.png')
        );
    }

    public function testCachedNullIsReturnedAsNull(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('load')->willReturn('null');
        $cache->expects($this->never())->method('save');
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->expects($this->never())->method('getDirectoryRead');

        $this->assertNull($this->registry($cache, $filesystem)->getDimensions('/media/a.png'));
    }

    public function testCorruptCacheEntryFallsBackToDisk(): void
    {
        $this->png('media/a.png', 8, 2);
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('load')->willReturn('{not json');
        $cache->expects($this->once())->method('save')->with('{"width":8,"height":2}');

        $this->assertSame(
            ['width' => 8, 'height' => 2],
            $this->registry($cache)->getDimensions('/media/a.png')
        );
    }
}
