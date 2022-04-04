<?php

namespace App\Controller\operativo;

use App\Controller\Permisos;
use App\Entity\Empresa;
use App\Entity\Invitaciones;
use App\Form\InvitacionesType;
use App\Repository\EmpresaRepository;
use App\Repository\InvitacionesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/invitaciones")
 */
class InvitacionesInternasController extends AbstractController
{
    private $invitacionesRepository;
    /**
     * InvitacionesController constructor.
     */
    public function __construct(InvitacionesRepository $invitacionesRepository)
    {
        $this->invitacionesRepository = $invitacionesRepository;
    }

    public function invitar(Invitaciones $invitacion){
        $cod = $invitacion->getId().'@'.$invitacion->getUsuario()->getEmpresa()->getId();
        $invitacion->setLink(base64_encode($cod));
    }

    /**
     * @Route("/{link}", name="invitaciones_internas_index", methods={"GET"})
     */
    public function index($link): Response
    {

        if($this->getUser()->getEmpresa()->getLink() == $link)
            return $this->render('invitaciones/solicitudes.html.twig', [
                'invitaciones' => $this->getUser()->getInvitacionesActivas(),// $invitacionesRepository->findAll(),
                'link'=>$this->getUser()->getLinkAcceso()
            ]);
        else
            return $this->redirectToRoute('no_permiso', [], Response::HTTP_SEE_OTHER);
        /*
        //$empresa = $this->getDoctrine()->getRepository(Empresa::class)->findOneBy(['link'=>$link]);
        $empresa = $empresaRepository->findOneBy(['link'=>$link]);
        if ($permisos->has_perm($this->getUser(),$empresa))
            return $this->render('invitaciones/solicitudes.html.twig', [
                'invitaciones' => $this->getUser()->getInvitacionesActivas(),// $invitacionesRepository->findAll(),
                'link'=>$this->getUser()->getLinkAcceso()
            ]);
        else
            return $this->redirectToRoute('no_permiso', [], Response::HTTP_SEE_OTHER);
        */
    }

    /**
     * @Route("/new", name="invitaciones_internas_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invitacione = new Invitaciones();
        $form = $this->createForm(InvitacionesType::class, $invitacione);
        $form->handleRequest($request);

        $user = $this->getUser();
        if ($this->isCsrfTokenValid('invitacion'.$user->getEmpresa()->getLink(), $request->request->get('_token'))) {
        //if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($invitacione->getNombre());
            //$invitacione->setLink($user->getEmpresa()->getLink());
            $this->invitar($invitacione);
            $entityManager->persist($invitacione);
            $entityManager->flush();
            // ENVIAR UN CORREO CON LA INVITACION

            return $this->redirectToRoute('invitaciones_internas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('invitaciones/new.html.twig', [
            'invitacione' => $invitacione,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/reenviar", name="invitaciones_internas_reenviar", methods={"GET"})
     */
    public function reenviar(Invitaciones $invitacion): Response
    {
        if ($this->getUser()->getId() == $invitacion->getUsuario()->getId()){
            //$invitacione->setLink('/register/user/empresa/'. $invitacione->getLink());
            $this->invitar($invitacion);
            return $this->render('invitaciones/show.html.twig', [
                'invitacione' => $invitacion,
            ]);
        }
        else
            return $this->redirectToRoute('no_permiso', [], Response::HTTP_SEE_OTHER);

    }


    /**
     * @Route("/delete/{id}", name="invitaciones_internas_delete", methods={"GET"})
     */
    public function delete(Request $request, Invitaciones $invitacione, EntityManagerInterface $entityManager): Response
    {
        if ($invitacione->getUsuario()->getId() == $this->getUser()->getId()){
        //if ($this->isCsrfTokenValid('delete'.$invitacione->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invitacione);
            $entityManager->flush();
            return $this->redirectToRoute('invitaciones_internas_index', [], Response::HTTP_SEE_OTHER);
        }
        else
            return $this->redirectToRoute('no_permiso', [], Response::HTTP_SEE_OTHER);


    }
}
