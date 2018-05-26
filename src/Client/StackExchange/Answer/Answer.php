<?php declare(strict_types=1);

namespace BlendExchange\StackExchange\Answer;

final class Answer 
{
    public function __construct( ) {
        
    }

    public function getAccepted() : ?bool
    {
        return $this->accepted ?? null;
    }

    public function getAnswerId() : ?int
    {
        return $this->answer_id;
    }

    public function getBody() : ?string
    {
        return $this->body ?? null;
    }

    public function getBodyMarkdown() : ?string
    {
        return $this->body_markdown ?? null;
    }

    public function getCanFlag() : ?bool
    {
        return $this->can_flag ?? null;
    }

    public function getOwner() : ?ShallowUser
    { 
        $this->owner ?? null;
    }
}