<?php

namespace App\Controller;

use App\Entity\ActorEconomico;
use App\Entity\Empresa;
use App\Form\EmpresaType;
use App\Repository\EmpresaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/solicitud")
 */
class SolicitudEmpresaController extends AbstractController
{


    /**
     * @Route("/nueva/{id}", name="empresa_solicitud", methods={"GET", "POST"})
     */
    public function new_solicitud(Request $request, EntityManagerInterface $entityManager,ActorEconomico $actorEconomico): Response
    {
        $empresa = new Empresa();

        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);
        if ($this->isCsrfTokenValid('empresa_solicitud', $request->request->get('_token'))) {
        //if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($actorEconomico->getId());
            $empresa->setNombre($actorEconomico->getNombre());
            $empresa->setTipo('empresarial');
            $empresa->setActoreconomico($actorEconomico);
            $entityManager->persist($empresa);
            $entityManager->flush();
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('empresa/page-register.html.twig', [
            'empresa' => $empresa,
            'actor'=>$actorEconomico
            //'form' => $form,
        ]);
    }

    /**
     * @Route("/print/terminos", name="empresa_print_terminos", methods={"GET"})
     */
    public function print_terminos(EmpresaRepository $empresaRepository): Response
    {
        return $this->render('empresa/page-print-terms.html.twig');
    }

}
