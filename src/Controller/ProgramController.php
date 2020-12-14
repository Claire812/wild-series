<?php


namespace App\Controller;


use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Service\Slugify;
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
    public function new(Request $request, Slugify $slugify) : Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            return $this->redirectToRoute('program_index');
        }


        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug": "slug"}} )
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
     * @Route ("/{programSlug}/seasons/{season_id}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}} )
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
     * @Route ("/{programSlug}/seasons/{season_id}/episodes/{episodeSlug}", name="episode_show")
     * @ParamConverter ("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter ("season", class="App\Entity\Season", options={"mapping": {"season_id": "id"}})
     * @ParamConverter ("episode", class="App\Entity\Episode", options={"mapping": {"episodeSlug": "slug"}})
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
