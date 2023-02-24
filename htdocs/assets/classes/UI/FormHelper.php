<?php

declare(strict_types=1);

namespace NMSTracker\UI;

use Application_Formable;
use Application_Formable_RecordSettings_Setting;
use HTML_QuickForm2_Element_InputText;
use HTML_QuickForm2_Element_Textarea;

class FormHelper
{
    private Application_Formable $formable;

    public function __construct(Application_Formable $formable)
    {
        $this->formable = $formable;
    }

    public function injectLabel(Application_Formable_RecordSettings_Setting $setting, string $label) : HTML_QuickForm2_Element_InputText
    {
        $el = $this->formable->addElementText($setting->getName(), $label);
        $el->addFilterTrim();
        $el->addClass('input-xxlarge');

        $this->formable->makeLengthLimited($el, 0, 160);
        $this->formable->addRuleNameOrTitle($el);

        return $el;
    }

    public function injectComments(Application_Formable_RecordSettings_Setting $setting, string $label) : HTML_QuickForm2_Element_Textarea
    {
        $el = $this->formable->addElementTextarea($setting->getName(), $label);
        $el->setRows(3);
        $el->addClass('input-xxlarge');
        $el->setComment((string)sb()
            ->t(
                'You may use %1$s to add formatting.',
                sb()->link('Markdown', 'https://www.markdownguide.org/basic-syntax/', true)
            )
        );

        $this->formable->addRuleNoHTML($el);

        return $el;
    }
}
