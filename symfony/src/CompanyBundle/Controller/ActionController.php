<?php

namespace CompanyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ActionController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function inspectorsAutocompleteAction(Request $request)
    {
        $paginator = $this->getDoctrine()->getRepository('CompanyBundle:Inspector')
            ->findAutocompleteInspector(
                $request->get('q'),
                $request->get('page'),
                $request->get('page_limit'))
        ;
        $data      = ['results' => []];
        foreach ($paginator as $certification) {
            $data['results'][] = [
                'id'   => $certification->getId(),
                'text' => $certification->__toString()
            ];
        }
        $data['more'] = $paginator->count() < $request->get('page_limit') ? false : true;

        return new JsonResponse($data);
    }
}