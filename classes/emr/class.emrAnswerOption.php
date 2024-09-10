<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrAnswerOption
{
    /**
     * @var int[]
     */
    protected array $answeringActiveIds = [];
    protected string $title = '';
    protected float $points = 0;

    public function hasActiveIdAnswered(int $activeId): bool
    {
        return isset($this->answeringActiveIds[$activeId]);
    }

    public function addAnsweringActiveId(int $activeId): void
    {
        $this->answeringActiveIds[$activeId] = $activeId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

    public function setPoints(float $points)
    {
        $this->points = $points;
    }

    public function hasPoints(): bool
    {
        return $this->getPoints() > 0;
    }
}
