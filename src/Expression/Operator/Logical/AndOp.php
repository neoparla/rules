<?php

namespace Superruzafa\Rules\Expression\Operator\Logical;

use Superruzafa\Rules\Expression;
use Superruzafa\Rules\Expression\Operator;
use Superruzafa\Rules\Expression\Operator\OperatorException;

class AndOp extends Operator
{
    /** {@inheritdoc} */
    public function getName()
    {
        return 'and';
    }

    /** {@inheritdoc} */
    public function evaluate()
    {
        if (count($this->operands) < 1) {
            throw new OperatorException('Logical And operator requires at least one operand');
        }

        foreach ($this->operands as $operand) {
            if (!$operand->evaluate()) {
                return false;
            }
        }
        return true;
    }

    /** {@inheritdoc} */
    public function getNativeExpression()
    {
        if (count($this->operands) < 1) {
            throw new OperatorException('Logical And operator requires at least one operand');
        }

        $operands = array_map(function(Expression $operand) {
            return $operand->getNativeExpression();
        }, $this->operands);
        $operands = array_unique($operands);

        if (1 == count($operands)) {
            return $operands[0];
        } else {
            return sprintf('(%s)', implode(' && ', $operands));
        }
    }
}