<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route ("/", name="index")
     * @return Response A response instance
     */
    public function index()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            -> findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index');

        }

        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);

    }

    /**
     * @Route("/{categoryName}", name="show")
     * @return Response
     */

    public function show(string $categoryName) : Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            -> findOneBy(['name' => $categoryName]);
        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : '.$categoryName.' found in category\'s table.'
            );
        }
        if ($category) {
            $seriesByCategory = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findBy(['category' => $category], ['id'=>'DESC'], 3);
        }

        return $this->render('category/show.html.twig', [
            'series_by_category' => $seriesByCategory,
        ]);


    }



}