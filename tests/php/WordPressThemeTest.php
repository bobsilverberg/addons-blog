<?php

declare(strict_types=1);

use PHPUnit\Framework\DOMTestCase;

final class WordPressThemeTest extends DOMTestCase
{
    public static function setUpBeforeClass(): void
    {
        if (!is_file(__DIR__ . '/../../build/style.css')) {
            throw new \RuntimeException(
                'You should run `yarn build:wptheme` first'
            );
        }

        require __DIR__ . '/fake-template-functions.php';
    }

    public function testSinglePage(): void
    {
        $html = $this->render('single.php', 1);

        $this->assertSelectEquals('.Post-title', 'some title', 1, $html);
        $this->assertSelectEquals('.Content-wrapper', 'some content', 1, $html);
        $this->assertSelectEquals('.Author', 'some author', 1, $html);
        $this->assertSelectCount('.Footer', 1, $html);
        $this->assertStringContainsString(
            'src="/path/to/template/dir/assets/js/bundle.js',
            $html
        );
    }

    public function testIndexPage(): void
    {
        $html = $this->render('index.php', 2);

        $this->assertSelectEquals('.post-title', 'some title', 2, $html);
        $this->assertSelectEquals('.excerpt', 'some content', 2, $html);
        $this->assertSelectCount('.Footer', 1, $html);
        $this->assertStringContainsString(
            'src="/path/to/template/dir/assets/js/bundle.js',
            $html
        );
    }

    private function render($template, $posts = 0): string
    {
        global $nb_posts;
        $nb_posts = $posts;

        ob_start();
        @include __DIR__ . '/../../build/' . $template;

        return ob_get_clean();
    }
}
