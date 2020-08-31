<?php

namespace Calendar\View\Helper\Cell\Render;

use Square\Entity\Square;
use Zend\View\Helper\AbstractHelper;

class OccupiedForVisitors extends AbstractHelper
{

    public function __invoke(array $reservations, array $cellLinkParams, Square $square, $user = null)
    {
        $view = $this->getView();
		$teacher = false;
		$constructions = false;

        $reservationsCount = count($reservations);

        if ($reservationsCount > 0) {
            $counter = 0;
            $cellLabel = "";
            
            foreach ($reservations as $reservation) {
                $counter += 1;
                $booking = $reservation->needExtra('booking');
				if ($booking->getMeta('teacher') == 1) {
					$teacher = true;
				}
				if ($booking->getMeta('constructions') == 1) {
					$constructions = true;
				}
                
                if ($booking) {
                    $bookungUser = $booking->getExtra('user');
                    if ($bookungUser) {
                        if ($bookungUser->can('user.maintenance')) {
                            $maintenance = true;
                        }
                    }
                }

                $quantity += $booking->need('quantity');
           
                if ($square->getMeta('public_names', 'false') == 'true') {
                    $cellLabel .= $booking->needExtra('user')->need('alias');
                } else if ($square->getMeta('private_names', 'false') == 'true' && $user) {
                    if ($user->getMeta('namedisplay') == 'shortLastname') {
                        $cellLabel .= $booking->needExtra('user')->getMeta('firstname') . " " . substr($booking->needExtra('user')->getMeta('lastname'), 0, 1) . ".";
                    } else if ($user->getMeta('namedisplay') == 'fullName') {
                        $cellLabel .= $booking->needExtra('user')->getMeta('firstname') . " " . $booking->needExtra('user')->getMeta('lastname');
                    } else {
                        $cellLabel .= substr($booking->needExtra('user')->getMeta('firstname'), 0, 1) . ". " . $booking->needExtra('user')->getMeta('lastname');
                    }
                } else {
                    $cellLabel = $this->view->t('Occupied');
                }               
                
                if ($counter < $reservationsCount) {
                    $cellLabel .= '</div><div class="cc-label ">';
                }
            }
            $style = 'cc-single cc-count-' . $counter;
        } else {
            $reservation = current($reservations);
            $booking = $reservation->needExtra('booking');
            if ($booking->getMeta('constructions') == 1) {
                $constructions = true;
            }

            if ($square->getMeta('public_names', 'false') == 'true') {
                $cellLabel = $booking->needExtra('user')->need('alias');
            } else if ($square->getMeta('private_names', 'false') == 'true' && $user) {
                $cellLabel = substr($booking->needExtra('user')->getMeta('firstname'), 0, 1) . ". " . $booking->needExtra('user')->getMeta('lastname');
            } else {
                $cellLabel = null;
            }

            $cellGroup = ' cc-group-' . $booking->need('bid');

            switch ($booking->need('status')) {
                case 'single':
                    if (! $cellLabel) {
                        $cellLabel = $this->view->t('Occupied');
                    }

                    $style = 'cc-single' . $cellGroup;
                    break;
                case 'subscription':
                    if (! $cellLabel) {
                        $cellLabel = $this->view->t('Subscription');
                    }

                    $style = 'cc-multiple' . $cellGroup;
                    break;
=======
            }
        }
        if ($constructions) {
            $style .= " cc-constructions";
        }
        if ($teacher) {
            $style .= " cc-teacher";
        }
        return $view->calendarCellLink($cellLabel, $view->url('square', [], $cellLinkParams), $style);
    }

}
