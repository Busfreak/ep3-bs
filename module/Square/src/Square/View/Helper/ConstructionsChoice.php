<?php

namespace Square\View\Helper;

use Base\Manager\OptionManager;
use Square\Entity\Square;
use Zend\View\Helper\AbstractHelper;

class ConstructionsChoice extends AbstractHelper
{

    protected $optionManager;

    public function __construct(OptionManager $optionManager)
    {
        $this->optionManager = $optionManager;
    }

    public function __invoke(Square $square, array $bookings)
    {
        $quantityAvailable = $square->need('capacity');

        $withConstructions = false;
        foreach ($bookings as $booking) {
            if ($booking->getMeta('constructions') == 1) {
                $withConstructions = true;
            }
            $quantityAvailable -= $booking->need('quantity');
        }

        $view = $this->getView();
        $html = '';

        if (!$withConstructions) {
            $html .= '<label for="sb-constructions" style="margin-right: 8px;">';
            $html .= $view->t('With Constructions?');
            $html .= '</label>';

            $html .= '<select id="sb-constructions" style="min-width: 64px;">';

            $html .= '<option value="0">Nein</option>';
            $html .= '<option value="1">Ja</option>';

            $html .= '</select>';
        } else {
            $html .= $view->t('This period is already booked with constructions.');
        }

        $quantityOccupied = $square->need('capacity') - $quantityAvailable;

        return $html;
    }

}