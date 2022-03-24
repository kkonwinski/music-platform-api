<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Band;
use App\Message\PromotedEmailMessage;
use App\Service\FileUpload;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/album")
 */
class AlbumController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/add", name="api_album_new", methods={"POST"})
     */
    public function add(Request $request, FileUpload $fileUpload, MessageBusInterface $bus): JsonResponse
    {
        $title = $request->request->get("title");
        $year = $request->request->get("year");
        $cover = $request->files->get("cover");
        $bandId = (int)$request->request->get("band");
        $isPromoted = $request->request->get("isPromoted");
        if (empty($title) || empty($year) || empty($cover) || empty($bandId)) {
            throw new NotFoundHttpException("Expecting mandatory parameters!");
        }

        $fileName = $fileUpload->upload($request->files->get("cover"));


        $bandObject = $this->entityManager->getRepository(Band::class)->findOneBy(["id" => $bandId]);
        if (!$bandObject) {
            throw new NotFoundHttpException("User not found!!!");
        }
        $album = new Album();
        $album->setTitle($title);
        $album->setCover($fileName);

        $album->setYear($year);
        $album->setBand($bandObject);

        if ($isPromoted !== null) {
            $album->setIsPromoted((bool)$isPromoted);
        }
        $this->entityManager->getRepository(Album::class)->add($album);

        if ($isPromoted === null) {
            $bus->dispatch(new PromotedEmailMessage($album->getId()));
        }


        return new JsonResponse(["message" => "Album created"], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="api_album_get", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        if (empty($id)) {
            throw new NotFoundHttpException("Expecting mandatory parameters!");
        }
        /** @var $album Album */
        $album = $this->entityManager->getRepository(Album::class)->findAlbumById($id);

        return new JsonResponse($album, Response::HTTP_OK);
    }

    /**
     * @Route("/all", name="api_album_all", methods={"GET"})
     */
    public function all(): JsonResponse
    {
        /** @var $album Album */
        $albums = $this->entityManager->getRepository(Album::class)->findAlbums();

        return new JsonResponse($albums, Response::HTTP_OK);
    }

    /**
     * @Route("/remove/{album}", name="api_album_remove", methods={"DELETE"})
     */
    public function remove(Album $album): JsonResponse
    {

        try {
            $this->entityManager->getRepository(Album::class)->remove($album);
        } catch (OptimisticLockException|ORMException $e) {
            $this->json($e->getMessage());
        }

        return new JsonResponse(["message" => "Album deleted!!!"], Response::HTTP_OK);
    }
}
