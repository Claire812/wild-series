<?php

namespace App\Controller;


use App\Entity\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/actor", name="actor_")
 */

class ActorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */

    public function index(): Response
    {
        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();

        return $this->render('actor/index.html.twig', [
            'actors' => $actors,
        ]);
    }

    /**
     * @Route("/{actor_id}", name="show")
     * @ParamConverter ("actor", class="App\Entity\Actor", options={"mapping": {"actor_id":"id"}} )
     * @return Response
     */

    public function show(Actor $actor): Response
    {

        if (!$actor) {
            throw $this->createNotFoundException(
                'No program with id : ' . $actor . ' found in actor\'s table.'
            );
        }
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);


    }
}
