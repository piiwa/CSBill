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

namespace CSBill\NotificationBundle\Notification\Handler;

use CSBill\NotificationBundle\Notification\TwilioNotification;
use CSBill\SettingsBundle\SystemConfig;
use Namshi\Notificator\Notification\Handler\HandlerInterface;
use Namshi\Notificator\NotificationInterface;
use Twilio\Rest\Client;

class TwilioHandler implements HandlerInterface
{
    /**
     * @var Client
     */
    private $twilio;

    /**
     * @var SystemConfig
     */
    private $config;

    /**
     * @param Client       $twilio
     * @param SystemConfig $config
     */
    public function __construct(Client $twilio, SystemConfig $config)
    {
        $this->twilio = $twilio;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldHandle(NotificationInterface $notification)
    {
        return $notification instanceof TwilioNotification;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(NotificationInterface $notification)
    {
        $number = $this->config->get('sms/twilio/number');

        if (!empty($number)) {
            /* @var TwilioNotification $notification */
            $this->twilio
                ->messages
                ->create(
                    $notification->getRecipientNumber(),
                    [
                        'from' => $number,
                        'body' => $notification->getMessage(),
                    ]
                );
        }
    }
}
