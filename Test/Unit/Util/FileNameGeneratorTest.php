<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\Test\Unit\Util;

use PHPUnit\Framework\TestCase;
use Yireo\DumpCmsContent\Util\FileNameGenerator;

class FileNameGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $generator = new FileNameGenerator();
        foreach ($this->getIdentifiers() as $label => [$identifier, $expectedFileName]) {
            $this->assertSame($expectedFileName, $generator->generate('cms-block', 42, $identifier), $label);
        }
    }

    public function testDifferentEntitiesWithSameIdentifierGetDifferentFileNames(): void
    {
        $generator = new FileNameGenerator();
        $this->assertNotSame(
            $generator->generate('cms-block', 1, 'footer'),
            $generator->generate('cms-block', 2, 'footer')
        );
        $this->assertNotSame(
            $generator->generate('cms-page', 1, 'a/b'),
            $generator->generate('cms-page', 2, 'a_b')
        );
    }

    public function testLongIdentifierIsTruncated(): void
    {
        $fileName = (new FileNameGenerator())->generate('cms-page', 1, str_repeat('a', 500));
        $this->assertSame('cms-page-1-' . str_repeat('a', 100) . '.html', $fileName);
    }

    /**
     * @return array<string, array{string, string}>
     */
    private function getIdentifiers(): array
    {
        return [
            'plain' => ['footer-links_1', 'cms-block-42-footer-links_1.html'],
            'parent traversal' => ['../x', 'cms-block-42-x.html'],
            'nested traversal' => ['a/../../b', 'cms-block-42-a_b.html'],
            'absolute path' => ['/etc/passwd', 'cms-block-42-etc_passwd.html'],
            'backslash traversal' => ['..\\x', 'cms-block-42-x.html'],
            'null byte' => ["x\0y", 'cms-block-42-x_y.html'],
            'extension' => ['foo.php', 'cms-block-42-foo_php.html'],
            'subfolder' => ['customer/faq', 'cms-block-42-customer_faq.html'],
            'newline' => ["evil\n", 'cms-block-42-evil.html'],
            'only dots' => ['..', 'cms-block-42-empty.html'],
            'empty' => ['', 'cms-block-42-empty.html'],
        ];
    }
}
