<?php

namespace App\Controller\admin;

use App\Entity\ActorEconomico;
use App\Entity\Empresa;
use App\Form\EmpresaType;
use App\Repository\EmpresaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @Route("/admin/empresa")
 */
class EmpresaController extends AbstractController
{

    /**
     * @Route("/", name="empresa_index", methods={"GET"})
     */
    public function index(EmpresaRepository $empresaRepository): Response
    {
        return $this->render('empresa/index.html.twig', [
            'empresas' => $empresaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="empresa_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $empresa = new Empresa();
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($empresa);
            $entityManager->flush();

            return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('empresa/new.html.twig', [
            'empresa' => $empresa,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="empresa_show", methods={"GET"})
     */
    public function show(Empresa $empresa): Response
    {
        return $this->render('empresa/show.html.twig', [
            'empresa' => $empresa,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="empresa_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Empresa $empresa, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('empresa/edit.html.twig', [
            'empresa' => $empresa,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="empresa_delete", methods={"POST"})
     */
    public function delete(Request $request, Empresa $empresa, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$empresa->getId(), $request->request->get('_token'))) {
            $entityManager->remove($empresa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/aprobar/solicitud/{id}", name="empresa_aprobar_solicitud", methods={"GET", "POST"})
     */
    public function aprobar_solicitud(Request $request, Empresa $empresa, EntityManagerInterface $entityManager): Response
    {
        $cod = $empresa->getId().'-'.$empresa->getActoreconomico()->getCodigo();
        $empresa->setLink(base64_encode($cod));
        $entityManager->flush();
        // aqui debe enviarse un correo al representante para que se registre
        // $empresa->getLink()
        // con un link de registro

        return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/reenviar/solicitud/{id}", name="empresa_reenviar_solicitud", methods={"GET", "POST"})
     */
    public function reenviar_solicitud(Empresa $empresa, EntityManagerInterface $entityManager): Response
    {
        if (is_null($empresa->getLink())){
            $cod = $empresa->getId().'-'.$empresa->getActoreconomico()->getCodigo();
            $empresa->setLink(base64_encode($cod));
            $entityManager->flush();
        }
        // aqui debe enviarse un correo al representante para que se registre
        // $empresa->getLink()
        // con un link de registro

        return $this->redirectToRoute('empresa_index', [], Response::HTTP_SEE_OTHER);
    }

}
