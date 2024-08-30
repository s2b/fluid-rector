<?php

namespace Praetorius\FluidRector\Tests\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class UseRenderingContextViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        return $renderingContext->getVariableProvider()->get('test');
    }
}

?>
-----
<?php

namespace Praetorius\FluidRector\Tests\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class UseRenderingContextViewHelper extends AbstractViewHelper
{
    public function render(): string
    {
        return $this->renderingContext->getVariableProvider()->get('test');
    }
}

?>