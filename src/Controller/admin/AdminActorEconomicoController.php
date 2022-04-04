<?php

namespace App\Controller\admin;

use App\Entity\ActorEconomico;
use App\Form\ActorEconomicoType;
use App\Repository\ActorEconomicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin/actor/economico")
 */
class AdminActorEconomicoController extends AbstractController
{
    private $em;
    private $paginator;
    public function __construct(EntityManagerInterface $em, PaginatorInterface $pi)
    {
        $this->em = $em;
        $this->paginator = $pi;
    }


    /**
     * @Route("/index/{forma}", name="admin_actor_economico_index", methods={"GET"})
     */
    public function index(Request $request,ActorEconomicoRepository $actorEconomicoRepository,$forma): Response
    {
        $txt = $request->query->get('k');
        $query = $actorEconomicoRepository->find_por_forma_roganizativa($forma,$txt);

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            100
        );
        $paginas = ceil($pagination->getTotalItemCount()/ $pagination->getItemNumberPerPage());
        return $this->render('actor_economico/page-search.html.twig', [
            'actor_economicos' => $pagination,
            'formas'=>$actorEconomicoRepository->getFormasOrganizativas(),
            'paginas'=>$paginas
        ]);
    }

    /**
     * @Route("/new", name="actor_economico_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $actorEconomico = new ActorEconomico();
        $form = $this->createForm(ActorEconomicoType::class, $actorEconomico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($actorEconomico);
            $entityManager->flush();

            return $this->redirectToRoute('actor_economico_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('actor_economico/new.html.twig', [
            'actor_economico' => $actorEconomico,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="actor_economico_show", methods={"GET"})
     */
    public function show(ActorEconomico $actorEconomico): Response
    {
        return $this->render('actor_economico/show.html.twig', [
            'actor_economico' => $actorEconomico,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="actor_economico_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ActorEconomico $actorEconomico, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActorEconomicoType::class, $actorEconomico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('actor_economico_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('actor_economico/edit.html.twig', [
            'actor_economico' => $actorEconomico,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="actor_economico_delete", methods={"POST"})
     */
    public function delete(Request $request, ActorEconomico $actorEconomico, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actorEconomico->getId(), $request->request->get('_token'))) {
            $entityManager->remove($actorEconomico);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actor_economico_index', [], Response::HTTP_SEE_OTHER);
    }
}
