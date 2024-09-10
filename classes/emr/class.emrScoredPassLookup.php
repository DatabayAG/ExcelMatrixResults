<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrScoredPassLookup
{
    /**
     * @var array<int, int|null>
     */
    protected array $scoredPassByActiveId = [];

    protected function load(int $activeId): ?int
    {
        return ilObjTest::_getResultPass($activeId);
    }

    public function get(int $activeId): ?int
    {
        if (!array_key_exists($activeId, $this->scoredPassByActiveId)) {
            $this->scoredPassByActiveId[$activeId] = $this->load($activeId);
        }

        return $this->scoredPassByActiveId[$activeId];
    }
}
