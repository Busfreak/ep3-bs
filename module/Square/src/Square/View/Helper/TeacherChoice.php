<?php

namespace Square\View\Helper;

use Base\Manager\OptionManager;
use Square\Entity\Square;
use Zend\View\Helper\AbstractHelper;

class TeacherChoice extends AbstractHelper
{

    protected $optionManager;

    public function __construct(OptionManager $optionManager)
    {
        $this->optionManager = $optionManager;
    }

    public function __invoke(Square $square, array $bookings)
    {
        $quantityAvailable = $square->need('capacity');
        $teacherAvailable = $square->getMeta('max_teachers');
        $withConstructions = false;

        foreach ($bookings as $booking) {
            $quantityAvailable -= (int)$booking->need('quantity');
            $teacherAvailable -= (int)$booking->getMeta('teacher');

            if ($booking->getMeta('constructions') == 1) {
                $withConstructions = true;
            }
        }

        $view = $this->getView();
        $html = '';

        if ($teacherAvailable > 0 and !$withConstructions) {
            $html .= '<label for="sb-teacher" style="margin-right: 8px;">';
            $html .= $view->t('With Teacher?');
            $html .= '</label>';

            $html .= '<select id="sb-teacher" style="min-width: 64px;">';

            $html .= '<option value="0">' . $view->t('No') . '</option>';
            $html .= '<option value="1">' . $view->t('Yes') . '</option>';

            $html .= '</select>';
        } else {
            $html .= $view->t('Teacher not possible.');
        }

        return $html;
    }

}
