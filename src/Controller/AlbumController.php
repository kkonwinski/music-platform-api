<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Band;
use App\Repository\AlbumRepository;
use App\Repository\BandRepository;
use App\Service\FileUpload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/album")
 */
class AlbumController extends AbstractController
{
    private AlbumRepository $albumRepository;

    public function __construct(AlbumRepository $albumRepository)
    {
        $this->albumRepository = $albumRepository;
    }

    /**
     * @Route("/add", name="api_album_new", methods={"POST"})
     */
    public function add(Request $request, FileUpload $fileUpload, BandRepository $bandRepository): JsonResponse
    {
        $title = $request->request->get("title");
        $year = $request->request->get("year");
        $cover = $request->files->get("cover");
        $bandId = (int)$request->request->get("band");
        if (empty($title) || empty($year) || empty($cover) || empty($bandId)) {
            throw new NotFoundHttpException("Expecting mandatory parameters!");
        }

        $fileName = $fileUpload->upload($request->files->get("cover"));


        $bandObject = $bandRepository->findOneBy(["id" => $bandId]);
        if (!$bandObject) {
            throw new NotFoundHttpException("User not found!!!");
        }
        $album = new Album();
        $album->setTitle($title);
        $album->setCover($fileName);
        if ($request->request->get("isPromoted") !== null) {
            $album->setIsPromoted((bool)$request->request->get("isPromoted"));
        }
        $album->setYear($year);
        $album->setBand($bandObject);
        $this->albumRepository->add($album);
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
        $album = $this->albumRepository->findAlbumById($id);

        return new JsonResponse($album, Response::HTTP_OK);
    }

    /**
     * @Route("/all", name="api_album_all", methods={"GET"})
     */
    public function all(): JsonResponse
    {
        /** @var $album Album */
        $albums = $this->albumRepository->findAlbums();

        return new JsonResponse($albums, Response::HTTP_OK);
    }

    /**
     * @Route("/remove/{album}", name="api_album_remove", methods={"DELETE"})
     */
    public function remove(Album $album): JsonResponse
    {

        $this->albumRepository->remove($album);

        return new JsonResponse(["message" => "Album deleted!!!"], Response::HTTP_OK);
    }
}
