<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Movie\Command\Handler\CommandHandlerInterface;
use App\Domain\Movie\Query\Handler\QueryItemsInterface;
use App\Entity\Movie;
use App\Form\Type\MovieType;
use App\Media\Resolver\ThumbnailResolver;
use App\Media\Uploader\Storage\AwsStorageUploader;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    public function __construct(
        private readonly CommandHandlerInterface $commandHandler,
        #[Autowire(env: "ENABLE_INDEXATION")] private readonly bool $indexationEnabled,
    ) {

    }

    #[Route('/add', name: 'app.movies.add')]
    public function index(Request $request, AwsStorageUploader $uploader): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandHandler->handle($movie, $form->get('cover')->getData());

            return $this->redirectToRoute('app.movies.all');
        }

        return $this->render('default/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/all', name: 'app.movies.all')]
    public function listMovies(QueryItemsInterface $query): Response
    {
        if (false === $this->indexationEnabled) {
            return $this->redirectToRoute('app.homepage');
        }
        $movies = $query->getAllMovies();

        return $this->render('default/all.html.twig', [
            'movies' => $movies,
        ]);
    }


    #[Route('/', name: 'app.homepage')]
    public function homepage(
        EntityManagerInterface $manager,
    ): Response {
        /** @var MovieRepository $repository */
        $repository = $manager->getRepository(Movie::class);
        $movies = $repository->getMovies();

        return $this->render('page/homepage.html.twig', [
            'movies' => $movies,
        ]);
    }
}
