<?php

namespace App\Controller\admin;

use App\Entity\Invitaciones;
use App\Form\InvitacionesType;
use App\Repository\InvitacionesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/invitaciones")
 */
class InvitacionesController extends AbstractController

{
    private $invitacionesRepository;
    /**
     * InvitacionesController constructor.
     */
    public function __construct(InvitacionesRepository $invitacionesRepository)
    {
        $this->invitacionesRepository = $invitacionesRepository;
    }

    /**
     * @Route("/", name="invitaciones_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('invitaciones/index.html.twig', [
            'invitaciones' => $this->invitacionesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="invitaciones_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invitacione = new Invitaciones();
        $form = $this->createForm(InvitacionesType::class, $invitacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invitacione);
            $entityManager->flush();

            return $this->redirectToRoute('invitaciones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('invitaciones/new.html.twig', [
            'invitacione' => $invitacione,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="invitaciones_show", methods={"GET"})
     */
    public function show(Invitaciones $invitacione): Response
    {
        return $this->render('invitaciones/show.html.twig', [
            'invitacione' => $invitacione,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="invitaciones_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Invitaciones $invitacione, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvitacionesType::class, $invitacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('invitaciones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('invitaciones/edit.html.twig', [
            'invitacione' => $invitacione,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="invitaciones_delete", methods={"POST"})
     */
    public function delete(Request $request, Invitaciones $invitacione, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invitacione->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invitacione);
            $entityManager->flush();
        }

        return $this->redirectToRoute('invitaciones_index', [], Response::HTTP_SEE_OTHER);
    }
}
