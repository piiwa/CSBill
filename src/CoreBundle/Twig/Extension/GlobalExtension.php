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

namespace CSBill\CoreBundle\Twig\Extension;

use Carbon\Carbon;
use CSBill\CoreBundle\CSBillCoreBundle;
use Money\Money;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GlobalExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Get global twig variables.
     *
     * @return array
     */
    public function getGlobals(): array
    {
        $globals = [
            'query' => $this->getQuery(),
            'app_version' => CSBillCoreBundle::VERSION,
            'app_name' => '',
            'settings' => [],
        ];

        if ($this->container->getParameter('installed')) {
            $config = $this->container->get('settings');
            $globals['app_name'] = $config->get('system/general/app_name');
            $globals['settings'] = $config->getAll();
        }

        return $globals;
    }

    /**
     * Get the url query.
     *
     * @return array
     */
    protected function getQuery(): array
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        if (!$request) {
            return [];
        }

        $params = array_merge($request->query->all(), $request->attributes->all());

        foreach (array_keys($params) as $key) {
            if (substr($key, 0, 1) == '_') {
                unset($params[$key]);
            }
        }

        return $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('percentage', [$this, 'formatPercentage']),
            new \Twig_SimpleFilter('diff', [$this, 'dateDiff']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('icon', [$this, 'displayIcon'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Displays an icon.
     *
     * @param string $iconName
     * @param array  $options
     *
     * @return string
     */
    public function displayIcon(string $iconName, array $options = []): string
    {
        $options = implode('-', $options);
        $class = sprintf('fa fa-%s', $iconName);

        if (!empty($options)) {
            $class .= ' '.$options;
        }

        return sprintf('<i class="%s"></i>', $class);
    }

    /**
     * @param int|float $amount
     * @param int       $percentage
     *
     * @return float|int
     */
    public function formatPercentage($amount, int $percentage = 0)
    {
        if ($percentage > 0) {
            if ($amount instanceof Money) {
                return $amount->multiply($percentage)->getAmount();
            }

            return $amount * $percentage;
        }

        return 0;
    }

    /**
     * Returns a human-readible diff for dates.
     *
     * @param \DateTime $date
     *
     * @return string
     */
    public function dateDiff(\DateTime $date): string
    {
        $carbon = Carbon::instance($date);

        return $carbon->diffForHumans();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'csbill_core.twig.globals';
    }
}
