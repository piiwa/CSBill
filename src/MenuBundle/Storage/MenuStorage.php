<?php

declare(strict_types=1);

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\MenuBundle\Storage;

class MenuStorage implements MenuStorageInterface
{
    /**
     * @var array
     */
    protected $list = [];

    /**
     * {@inheritdoc}
     */
    public function has(string $name): bool
    {
        return isset($this->list[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name): \SplPriorityQueue
    {
        if (!$this->has($name)) {
            $this->list[$name] = new \SplPriorityQueue();
        }

        return $this->list[$name];
    }
}
