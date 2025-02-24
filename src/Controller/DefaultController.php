<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Movie\Command\Handler\CommandHandlerInterface;
use App\Domain\Movie\Query\Handler\QueryInterface;
use App\Entity\Movie;
use App\Form\Type\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    public function __construct(private readonly CommandHandlerInterface $commandHandler)
    {

    }

    #[Route('/add', name: 'app.movies.add')]
    public function index(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandHandler->handle($movie);

            return $this->redirectToRoute('app.movies.all');
        }

        return $this->render('default/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/all', name: 'app.movies.all')]
    public function listMovies(QueryInterface $query): Response
    {
        $movies = $query->getItems();

        return $this->render('default/all.html.twig', [
            'movies' => $movies,
        ]);
    }
}
