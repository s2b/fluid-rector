<?php

namespace Praetorius\FluidRector\Tests\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class ImplicitContentArgumentNameViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    protected $contentArgumentName = 'value';

    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'a value');
    }
}

?>
-----
<?php

namespace Praetorius\FluidRector\Tests\Fixture;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class ImplicitContentArgumentNameViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    protected $contentArgumentName = 'value';

    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'a value');
    }
}

?>