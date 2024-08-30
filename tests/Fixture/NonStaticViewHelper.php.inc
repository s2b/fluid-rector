<?php

namespace Praetorius\FluidRector\Tests\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class NonStaticViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('arg1', 'string', '');
        $this->registerArgument('arg2', 'string', '');
    }

    public function render(): string
    {
        $var1 = $this->arguments['arg1'];
        if ($this->arguments['arg2']) {
            $var1 = $this->arguments['arg2'];
        }
        $var1 = $var1 ?? $this->renderChildren();
        $var2 = $this->renderingContext->getVariableProvider()->get('test');
        $var3 = array_map(fn ($item) => $this->renderingContext->getVariableProvider($item) ?? $item, $this->arguments['arg3']);
        return implode(', ', $var3);
    }
}

?>
-----
<?php

namespace Praetorius\FluidRector\Tests\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class NonStaticViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('arg1', 'string', '');
        $this->registerArgument('arg2', 'string', '');
    }

    public function render(): string
    {
        $var1 = $this->arguments['arg1'];
        if ($this->arguments['arg2']) {
            $var1 = $this->arguments['arg2'];
        }
        $var1 = $var1 ?? $this->renderChildren();
        $var2 = $this->renderingContext->getVariableProvider()->get('test');
        $var3 = array_map(fn ($item) => $this->renderingContext->getVariableProvider($item) ?? $item, $this->arguments['arg3']);
        return implode(', ', $var3);
    }
}

?>