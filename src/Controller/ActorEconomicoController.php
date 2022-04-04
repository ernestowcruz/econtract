<?php

namespace App\Controller;

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
 * @Route("/actor/economico")
 */
class ActorEconomicoController extends AbstractController
{
    private $em;
    private $paginator;
    public function __construct(EntityManagerInterface $em, PaginatorInterface $pi)
    {
        $this->em = $em;
        $this->paginator = $pi;
    }


    /**
     * @Route("/index/{forma}", name="actor_economico_index", methods={"GET"})
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
}
