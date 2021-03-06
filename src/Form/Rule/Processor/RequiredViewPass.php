<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RequiredViewPass implements FormRuleProcessorInterface
{
    protected static $requiredConstraintClasses = array(
        'Symfony\Component\Validator\Constraints\NotNull',
        'Symfony\Component\Validator\Constraints\NotBlank',
        'Symfony\Component\Validator\Constraints\Required',
    );

    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext)
    {
        $view = $processContext->getView();
        if (!isset($view->vars['required'])) {
            return;
        }

        // Check if the field is really required according to HTML validation
        // (aka the required for symfony form means it needs to be submitted but maybe null or "")
        $constraints = $processContext->getConstraints();
        foreach ($constraints as $constraint) {
            if (in_array(get_class($constraint), static::$requiredConstraintClasses, true)) {
                $view->vars['required'] = true;

                return;
            }
        }

        $view->vars['required'] = false;
    }
}
