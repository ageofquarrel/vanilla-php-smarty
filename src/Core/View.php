<?php

declare(strict_types=1);

namespace App\Core;

use Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(string $basePath)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($basePath . '/templates');
        $this->smarty->setCompileDir($basePath . '/storage/cache/smarty');
        $this->smarty->setCacheDir($basePath . '/storage/cache/smarty');
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        return $this->smarty->fetch($template);
    }
}
