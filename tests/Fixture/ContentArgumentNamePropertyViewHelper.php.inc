<?php

namespace Praetorius\FluidRector\Tests\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class ContentArgumentNamePropertyViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    protected $contentArgumentName = 'value';

    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'a value');
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        return $renderChildrenClosure();
    }
}

?>
-----
<?php

namespace Praetorius\FluidRector\Tests\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class ContentArgumentNamePropertyViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'a value');
    }

    public function render(): string
    {
        return $this->renderChildren();
    }
    public function getContentArgumentName(): string
    {
        return 'value';
    }
}

?>