<?php

namespace ToolsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ActionController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function languagesAutocompleteAction(Request $request)
    {
        $trans = $this->get('translator');
        $paginator = $this->getDoctrine()->getRepository('ToolsBundle:Language')
            ->findAutocompleteCertification(
                $request->get('q'),
                $request->get('page'),
                $request->get('page_limit'))
        ;
        $data      = ['results' => []];
        /** @var \ToolsBundle\Entity\Language $languages */
        foreach ($paginator as $languages) {
            $data['results'][] = [
                'id'   => $languages->getId(),
                'text' => $trans->trans($languages->__toString(), [], 'tools')
            ];
        }
        $data['more'] = $paginator->count() < $request->get('page_limit') ? false : true;

        return new JsonResponse($data);
    }
}
