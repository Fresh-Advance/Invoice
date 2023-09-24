<?php

namespace FreshAdvance\Invoice\Transition\Core;

interface LanguageInterface
{
    public function faForceSetTplLanguage(int $language): void;

    public function getTplLanguage(): int;
}
