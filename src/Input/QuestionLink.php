<?php declare(strict_types = 1);

namespace BlendExchange\Input;

final class QuestionLink
{
    private $link;

    public function __construct (string $link) {
        $this->link = $link;
    }

    /**
     * Gets the question ID for the question link
     * 
     * @return string|null
     */

    public function getId() : ?string
    {
        if ($this->link === null) {
            return null;
        }
        $matches = [];
        $state = (preg_match("/^https?:\/\/(?:meta\.)?blender.stackexchange.com\/q(?:uestions)?\/([0-9]+)(?:\/(?:[A-z\-#0-9\/_?=&]+|[0-9]+|))?$/", $this->link, $matches)) ? 1 : 0;
        return ($state) ? $matches[1] : null;
    }

    /**
     * Returns the question id in the format https://blender.stackexchange.com/q/[id]
     * 
     * @return string
     */

    public function sanitizedUrl() : string
    {
        return sprintf('https://blender.stackexchange.com/q/%s',$this->getId());
    }
}