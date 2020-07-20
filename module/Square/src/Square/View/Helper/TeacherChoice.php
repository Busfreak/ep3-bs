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

        $withTeacher = false;
        foreach ($bookings as $booking) {
            if ($booking->getMeta('teacher') == 1) {
                $withTeacher = true;
            }
            $quantityAvailable -= $booking->need('quantity');
        }

        $view = $this->getView();
        $html = '';

        if (!$withTeacher) {
            $html .= '<label for="sb-teacher" style="margin-right: 8px;">';
            $html .= $view->t('Mit Reitlehrer?');
            $html .= '</label>';

            $html .= '<select id="sb-teacher" style="min-width: 64px;">';

            $html .= '<option value="0">Nein</option>';
            $html .= '<option value="1">Ja</option>';

            $html .= '</select>';
        } else {
            $html .= 'Dieser Zeitraum ist bereits mit Reitlehrer gebucht.';
        }

        $quantityOccupied = $square->need('capacity') - $quantityAvailable;

        $askNames = $square->getMeta('teacher-ask-names');

        if ($askNames && $quantityAvailable > 1) {
            $askNamesSegments = explode('-', $askNames);

            $html .= '<div class="sb-teacher-names">';

            $html .= '<div class="separator separator-line"></div>';

            if (isset($askNamesSegments[0]) && $askNamesSegments[0] == 'optional') {
                $html .= sprintf('<p class="sb-teacher-names-mode gray" data-mode="optional">%s</p>',
                    $this->view->translate('The names of the other players are <b>optional</b>'));
            } else {
                $html .= sprintf('<p class="sb-teacher-names-mode gray" data-mode="required">%s</p>',
                    $this->view->translate('The names of the other players are <b>required</b>'));
            }

            for ($i = 2; $i <= $quantityAvailable; $i++) {
                $html .= sprintf('<div class="sb-teacher-name sb-teacher-name-%s" style="margin-bottom: 4px;">', $i);

                $html .= sprintf('<input type="text" name="sb-teacher-name-%1$s" id="sb-name-%1$s" value="" placeholder="%1$s. %2$s" style="min-width: 160px;">',
                    $i, $this->view->translate('Player\'s name'));

                if (isset($askNamesSegments[2]) && $askNamesSegments[2] == 'email') {

                    $html .= sprintf(' <input type="text" name="sb-teacher-email-%1$s" id="sb-teacher-email-%1$s" value="" placeholder="...%2$s" style="min-width: 160px;">',
                        $i, $this->view->translate('and email address'));
                }

                if ((isset($askNamesSegments[2]) && $askNamesSegments[2] == 'phone') ||
                    (isset($askNamesSegments[3]) && $askNamesSegments[3] == 'phone')) {

                    $html .= sprintf(' <input type="text" name="sb-teacher-phone-%1$s" id="sb-teacher-phone-%1$s" value="" placeholder="...%2$s" style="min-width: 160px;">',
                        $i, $this->view->translate('and phone number'));
                }

                $html .= '</div>';
            }

            $html .= '</div>';
        }

        return $html;
    }

}
