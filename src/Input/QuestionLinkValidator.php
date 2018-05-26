<?php declare(strict_types = 1);

namespace BlendExchange\Input;

use BlendExchange\Client\StackExchangeClient;

final class QuestionLinkValidator
{
    private $stackClient;

    public function __construct(StackExchangeClient $stackClient)
    {
        $this->stackClient = $stackClient;
    }

    /**
     * Validates the question link. Returns false if the question is invalid
     *
     * @return bool
     */

    public function validate(QuestionLink $questionLink) : bool
    {
        $errors = [];
        $question = $questionLink->getId();
        if ($question !== null) {
            $question = $this->stackClient->getQuestion($question);
        }
        return $question !== null;
    }
}
