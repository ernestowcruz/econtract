<?php

namespace App\Controller\operativo;

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
 * @Route("/division")
 */
class RegistroDivisionesController extends AbstractController
{

    /**
     * @Route("/index/{link}", name="divisiones_index", methods={"GET"})
     */
    public function index(EmpresaRepository $empresaRepository,$link): Response
    {
        if($this->getUser()->getEmpresa()->getLink() == $link) {
            return $this->render('empresa/page-divisiones.html.twig', [
                'divisiones' => $empresaRepository->getDivisiones($link),
            ]);
        }
        else
            return $this->redirectToRoute('no_permiso', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/nueva/{link}", name="division_nueva", methods={"GET", "POST"})
     */
    public function new_solicitud(Request $request, EntityManagerInterface $entityManager,$link): Response
    {
        if($this->getUser()->getEmpresa()->getLink() == $link) {
            $empresa = new Empresa();
            $actorEconomico = $this->getUser()->getEmpresa()->getActoreconomico();
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

            return $this->renderForm('empresa/page-register-division.html.twig', [
                'empresa' => $empresa,
                'actor' => $actorEconomico
                //'form' => $form,
            ]);
        }
        else
            return $this->redirectToRoute('no_permiso', [], Response::HTTP_SEE_OTHER);
    }


}
