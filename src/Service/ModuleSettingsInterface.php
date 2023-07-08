<?php

namespace FreshAdvance\Invoice\Service;

interface ModuleSettingsInterface
{
    public function getDocumentFooter(): string;

    public function getFilePrefix(): string;
}
