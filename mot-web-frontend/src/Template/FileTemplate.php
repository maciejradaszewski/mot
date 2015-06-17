<?php
namespace Dvsa\Mot\Frontend\Template;

class FileTemplate
{
    const INVALID_FILE_MESSAGE_TEMPLATE = "Invalid file name: %s";

    public function generateTemplateFromFile($filename, array $variables)
    {
        $template = file_get_contents($filename);
        $text = $this->generateTemplate($template, $variables);
        if (!$text) {
            throw new \InvalidArgumentException(sprintf(self::INVALID_FILE_MESSAGE_TEMPLATE, $filename));
        }
        return $text;
    }

    public function generateTemplate($template, array $variables)
    {
        if ($template) {
            foreach ($variables as $key => $value) {
                $template = str_replace('<% ' . $key . ' %>', $value, $template);
            }
        }
        return $template;
    }
}
