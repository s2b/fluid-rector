<?php

declare(strict_types=1);

namespace Praetorius\FluidRector;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\TraitUse;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3Fluid\Fluid\Tests\Functional\Fixtures\ViewHelpers\CompileWithContentArgumentAndRenderStaticExplicitSetArgumentNameForContentOverriddenResolveContentArgumentNameMethodViewHelper;
use TYPO3Fluid\Fluid\Tests\Functional\ViewHelpers\StaticCacheable\Fixtures\ViewHelpers\CompilableViewHelper;

class ObjectBasedViewHelpersRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        // what node types are we looking for?
        // pick from
        // https://github.com/rectorphp/php-parser-nodes-docs/
        return [Class_::class];
    }

    /**
     * @param Class_ $classNode
     */
    public function refactor(Node $classNode): ?Node
    {
        // Ignore ViewHelpers that test trait functionality
        if ($this->isNames($classNode, [
            CompileWithContentArgumentAndRenderStaticExplicitSetArgumentNameForContentOverriddenResolveContentArgumentNameMethodViewHelper::class,
            CompilableViewHelper::class,
        ])) {
            return null;
        }

        // Only refactor ViewHelper classes that implement RenderStatic traits
        $usesTrait = false;
        foreach ($classNode->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $trait) {
                // Skip ViewHelpers where content argument is determined automatically
                if ($this->isName($trait, CompileWithContentArgumentAndRenderStatic::class)) {
                    // $contentArgumentNameProperty = $classNode->getProperty('contentArgumentName');
                    // if ($contentArgumentNameProperty && $contentArgumentNameProperty->props !== []) {
                    //     $usesTrait = true;
                    //     break;
                    // }

                    $resolveContentArgumentNameMethod = $classNode->getMethod('resolveContentArgumentName');
                    if ($resolveContentArgumentNameMethod) {
                        $usesTrait = true;
                        break;
                    }
                }

                if ($this->isName($trait, CompileWithRenderStatic::class)) {
                    $usesTrait = true;
                    break;
                }
            }
        }

        if (!$usesTrait) {
            return null;
        }

        // Skip ViewHelpers without renderStatic() method
        $staticMethodNode = $classNode->getMethod('renderStatic');
        if (!$staticMethodNode instanceof ClassMethod || !$staticMethodNode->isStatic()) {
            return null;
        }

        // Replacements for renderStatic() method arguments
        $replacementParams = [
            'this->arguments',
            'this->renderChildren',
            'this->renderingContext',
        ];

        // Replace local variables in render function with object properties
        $this->traverseNodesWithCallable(
            $staticMethodNode->stmts,
            function (Node $variableNode) use ($staticMethodNode, $replacementParams): ?Node {
                if (!$variableNode instanceof Variable) {
                    return null;
                }

                foreach ($staticMethodNode->params as $i => $param) {
                    if ((string)$param->var->name == (string)$variableNode->name) {
                        $variableNode->name = new Identifier($replacementParams[$i]);
                    }
                }

                return $variableNode;
            }
        );

        // Rename method and make it non-static
        $staticMethodNode->params = [];
        $staticMethodNode->name = new Identifier('render');
        $staticMethodNode->flags = $staticMethodNode->flags ^ Modifiers::STATIC;

        // Rename content argument method
        $resolveContentArgumentNameMethod = $classNode->getMethod('resolveContentArgumentName');
        if ($resolveContentArgumentNameMethod instanceof ClassMethod) {
            $resolveContentArgumentNameMethod->name = new Identifier('getContentArgumentName');
        }

        // Remove traits
        foreach ($classNode->stmts as $stmtKey => $stmt) {
            if (!$stmt instanceof TraitUse) {
                continue;
            }
            foreach ($stmt->traits as $traitKey => $trait) {
                if ($this->isNames($trait, [CompileWithRenderStatic::class, CompileWithContentArgumentAndRenderStatic::class])) {
                    unset($stmt->traits[$traitKey]);
                }
            }
            if ($stmt->traits === []) {
                unset($classNode->stmts[$stmtKey]);
            }
        }

        return $classNode;
    }

    /**
     * This method helps other to understand the rule
     * and to generate documentation.
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate static ViewHelpers to object-based ViewHelpers', [
                new CodeSample(
                    // code before
                    'OLD',
                    // code after
                    'NEW'
                ),
            ]
        );
    }
}
