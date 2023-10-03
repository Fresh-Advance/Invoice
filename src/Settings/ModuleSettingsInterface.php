<?php

namespace FreshAdvance\Invoice\Settings;

interface ModuleSettingsInterface
{
    public function getDocumentFooter(): string;

    public function getFilePrefix(): string;

    public function isForArchive(): bool;
}
