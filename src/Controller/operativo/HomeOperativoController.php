<?php

namespace App\Controller\operativo;

use App\Controller\Permisos;
use App\Entity\Empresa;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inicio")
 */
class HomeOperativoController extends AbstractController
{
    /**
     * @Route("/{link}", name="home_contratacion")
     */
    public function index(Permisos $permisos,$link): Response
    {
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->findOneBy(['link'=>$link]);
        if ($permisos->has_perm($this->getUser(),$empresa))
            return $this->render('contratacion/index.html.twig', [
            'controller_name' => 'HomeController',
            ]);
        else
            return $this->redirectToRoute('no_permiso', [], Response::HTTP_SEE_OTHER);

    }





}
