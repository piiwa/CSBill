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

namespace CSBill\SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="app_config")
 * @ORM\Entity(repositoryClass="CSBill\SettingsBundle\Repository\SettingsRepository")
 * @Gedmo\Loggable()
 * @UniqueEntity(fields={"key"})
 */
class Setting
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="setting_key", type="string", length=125, nullable=false, unique=true)
     *
     * @var string
     */
    protected $key;

    /**
     * @ORM\Column(name="setting_value", type="text", nullable=true)
     *
     * @var string
     */
    protected $value;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(name="field_type", type="string", nullable=false)
     *
     * @var string
     */
    protected $type;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Set key.
     *
     * @param string $key
     *
     * @return Setting
     */
    public function setKey(string $key): Setting
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value.
     *
     * @param mixed $value
     *
     * @return Setting
     */
    public function setValue($value): Setting
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Setting
     */
    public function setDescription(?string $description): Setting
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Setting
     */
    public function setType(string $type): Setting
    {
        $this->type = $type;

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->value;
    }
}
