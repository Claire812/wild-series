<?php


namespace App\Controller;


use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/programs", name="program_")
 */

class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();

            return $this->redirectToRoute('program_index');
        }


        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{program_id}", name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}} )
     * @return Response
     */
    public function show(Program $program): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $program]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program.' found in prorgram\'s table.'
            );
        } else {
            $seasons = $this->getDoctrine()
                ->getRepository(Season::class)
                -> findBy(['program' => $program]);
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);

    }

    /**
     * @Route ("/{program_id}/seasons/{season_id}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}} )
     * @ParamConverter ("season", class="App\Entity\Season", options={"mapping": {"season_id": "id"}} )
     *
     */
    public function showSeasons(Program $program, Season $season)
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $program]);
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id'=> $season]);
        $episodes =$this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $season]);


        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes
        ]);
    }

    /**
     * @Route ("/{program_id}/seasons/{season_id}/episodes/{episode_id}", name="episode_show")
     * @ParamConverter ("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
     * @ParamConverter ("season", class="App\Entity\Season", options={"mapping": {"season_id": "id"}})
     * @ParamConverter ("episode", class="App\Entity\Episode", options={"mapping": {"episode_id": "id"}})
     *
     */
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode
        ]);
    }
}