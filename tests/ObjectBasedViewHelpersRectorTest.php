<?php

declare(strict_types=1);

namespace Praetorius\FluidRector\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

class ObjectBasedViewHelpersRectorTest extends AbstractRectorTestCase
{
    public static function processRectorDataProvider(): iterable
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    #[Test]
    #[DataProvider('processRectorDataProvider')]
    public function processRector(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config.php';
    }
}