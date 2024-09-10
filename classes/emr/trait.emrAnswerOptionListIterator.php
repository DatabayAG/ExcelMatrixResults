<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

trait emrAnswerOptionListIterator
{
    /**
     * @var array<string, emrAnswerOption>
     */
    protected $answerOptions = [];

    protected function addAnswerOption(emrAnswerOption $answerOption, string $key): void
    {
        $this->answerOptions[$key] = $answerOption;
    }

    protected function getAnswerOption(string $key): emrAnswerOption
    {
        return $this->answerOptions[$key];
    }

    protected function answerOptionExists(string $key): bool
    {
        return strlen($key) && isset($this->answerOptions[$key]);
    }

    /**
     * @return emrAnswerOption|false
     */
    public function current(): mixed
    {
        return current($this->answerOptions);
    }

    /**
     * @return emrAnswerOption|false
     */
    public function next() : void
    {
        next($this->answerOptions);
    }

    /**
     * @return ?string
     */
    public function key() : mixed
    {
        return key($this->answerOptions);
    }

    /**
     * @return bool
     */
    public function valid() :bool
    {
        return key($this->answerOptions) !== null;
    }

    /**
     * @return emrAnswerOption|false
     */
    public function rewind() : void
    {
        reset($this->answerOptions);
    }

    public function getNumAnswers(): int
    {
        return count($this->answerOptions);
    }
}
