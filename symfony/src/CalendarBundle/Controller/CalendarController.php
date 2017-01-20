<?php

namespace CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use CalendarBundle\Repository\BookingsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use CalendarBundle\Entity\Booking;

class CalendarController extends Controller
{
    public function viewAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
                and ($url = $this->get('signedUp')->checkIfSignedUp($this)))
            {
                return $this->redirectToRoute($url);
            }
            return $this->render('CalendarBundle:Default:view.html.twig');
        }
        else
        {
   	     return $this->redirectToRoute('home_page_expert');
        }
    }

    public function getBookingAction()
    {
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
                and ($url = $this->get('signedUp')->checkIfSignedUp($this)))
            {
                return $this->redirectToRoute($url);
            }
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $calendarId = $user->getCalendar()->getId();
            $bookings = $em->getRepository('CalendarBundle:Booking')->getBookings($calendarId);
            $i = 0;
            foreach ($bookings as $booking)
            {
                $startString = date_format($booking['start'], 'Y-m-d H:i:s');
                $endString = date_format($booking['end'], 'Y-m-d H:i:s');
                $bookings[$i]['start'] = $startString;
                $bookings[$i]['end'] = $endString;
                $bookings[$i]['title'] = "BLOCKED"; //uniquement pour permettre au fullCalendar bundle d'afficher l'event, n'affecte pas notre bdd
                $i++;
            }
            $response = new Response(json_encode($bookings));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        else
        {
            throw new NotFoundHttpException($trans->trans('calendar.error.forbiddenAccess', array(), 'CalendarBundle'));
        }
    }

    public function addBookingAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
                and ($url = $this->get('signedUp')->checkIfSignedUp($this)))
            {
                return $this->redirectToRoute($url);
            }
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            if ($request->getMethod() == 'POST')
            {
                $data = $request->request;
                $start = $data->get('start');
                $calendar = $user->getCalendar();
                $calendarId = $user->getCalendar()->getId();
                $repository = $em->getRepository('CalendarBundle:Booking');
                $result = $repository->getBookingsByStart($calendarId, $start);
                if (!$result)
                {
                    $start = new \Datetime($data->get('start'));
                    $end = new \Datetime($data->get('end'));
                    $booking = new Booking($calendar, $start, $end);
                    $booking->setStatus(0);
                    $em->persist($booking);
                    $em->flush();
                }
            }
            return $this->render('CalendarBundle:Default:view.html.twig');
        }
        else
        {
            throw new NotFoundHttpException($trans->trans('calendar.error.forbiddenAccess', array(), 'CalendarBundle'));
        }
    }

    public function removeBookingAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADVISOR')
                and ($url = $this->get('signedUp')->checkIfSignedUp($this)))
            {
                return $this->redirectToRoute($url);
            }
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('CalendarBundle:Booking');
            $user = $this->getUser();
            if ($request->getMethod() == 'POST')
            {
                $data = $request->request;
                $id = $data->get('id');
                if ($id == "undefined")
                {
                    $calendarId =  $user->getCalendar()->getId();
                    $start = $data->get('start');
                    $booking = $repository->getBookingsByStart($calendarId, $start);
                    $nextDay = new \Datetime($data->get('nextDay'));
                    $end = new \Datetime($data->get('end'));
                    if ($end != $nextDay) // si c'est un booking de + d'1 jour
                    {
                        $booking[0]->setStart($nextDay);
                    }
                    else
                    {
                        $em->remove($booking[0]);
                    }
                }
                else
                {
                    $booking = $repository->findOneById($id);
                    $nextDay = new \Datetime($data->get('nextDay'));
                    $end = new \Datetime($data->get('end'));
                    if ($end != $nextDay) // si c'est un booking de + d'1 jour
                    {
                        $booking->setStart($nextDay);
                    }
                    else
                    {
                        $em->remove($booking);
                    }
                }
                $em->flush();
            }
            return $this->render('CalendarBundle:Default:view.html.twig');
        }
        else
        {
            throw new NotFoundHttpException($trans->trans('calendar.error.forbiddenAccess', array(), 'CalendarBundle'));
        }
    }

    // public function editBookingAction(Request $request)
    // {
    //     if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
    //     {
    //         $em = $this->getDoctrine()->getManager();
    //         $repository = $em->getRepository('CalendarBundle:Booking');
    //         $user = $this->getUser();
    //         if ($request->getMethod() == 'POST')
    //         {
    //             $data = $request->request;
    //             $id = $data->get('id');
    //             if ($id == "undefined")
    //             {
    //                 $calendarId =  $user->getCalendar()->getId();
    //                 $startParent = $data->get('startParent');
    //                 $parent = $repository->getBookingsByStart($calendarId, $startParent);
    //                 $end = $data->get('startChild');
    //                 $bookingEnding = new \Datetime($end); // on coupe le parent en mettant son end a celui du fils
    //                 $parent[0]->setEnd($bookingEnding);
    //             }
    //             else
    //             {
    //                 $parent = $repository->findOneById($id);
    //                 $end = $data->get('startChild');
    //                 $bookingEnding = new \Datetime($end); // on coupe le parent en mettant son end a celui du fils
    //                 $parent->setEnd($bookingEnding);
    //             }
    //             $endChild = new \Datetime($data->get('endChild'));
    //             $endParent = new \Datetime($data->get('endParent'));
    //             if ($endChild != $endParent)
    //             {
    //                 $calendar = $user->getCalendar();
    //                 $childEvent = new Booking($calendar, $endChild, $endParent);
    //                 $childEvent->setStatus(0);
    //                 $em->persist($childEvent);
    //             }
    //             $em->flush();
    //         }
    //         return $this->render('CalendarBundle:Default:view.html.twig');
    //     }
//    }
}
