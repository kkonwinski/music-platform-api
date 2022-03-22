<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Track;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/track")
 */
class TrackController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/add", name="api_track_new", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {

        $title = $request->request->get("title");
        $url = $request->request->get("url");
        $albumId = (int)$request->request->get("album");

        if (empty($title)|| empty($url) || empty($albumId)) {
            throw new NotFoundHttpException("Expecting mandatory parameters!");
        }
        $albumObj = $this->entityManager->getRepository(Album::class)->findOneBy(["id"=>$albumId]);
        $track = new Track();
        $track->setTitle($title);
        $track->setUrl($url);
        $track->setAlbum($albumObj);
        $this->entityManager->getRepository(Track::class)->add($track);
        return new JsonResponse(["message" => "Track created"], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="api_track_get", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        if (empty($id)) {
            throw new NotFoundHttpException("Expecting mandatory parameters!");
        }
        /** @var $track Track */
        $track = $this->entityManager->getRepository(Track::class)->findTrackById($id);

        return new JsonResponse($track, Response::HTTP_OK);
    }

    /**
     * @Route("/all", name="api_track_all", methods={"GET"})
     */
    public function all(): JsonResponse
    {
        /** @var $track Track */
        $tracks = $this->entityManager->getRepository(Track::class)->findTracks();

        return new JsonResponse($tracks, Response::HTTP_OK);
    }

    /**
     * @Route("/remove/{track}", name="api_track_remove", methods={"DELETE"})
     */
    public function remove(Track $track): JsonResponse
    {

        try {
            $this->entityManager->getRepository(Track::class)->remove($track);
        } catch (OptimisticLockException | ORMException $e) {
            $this->json($e->getMessage());
        }

        return new JsonResponse(["message" => "Track deleted!!!"], Response::HTTP_OK);
    }
}
