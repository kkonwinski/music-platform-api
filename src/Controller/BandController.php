<?php

namespace App\Controller;

use App\Entity\Band;
use App\Repository\BandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/band")
 */
class BandController extends AbstractController
{
    private BandRepository $bandRepository;

    public function __construct(BandRepository $bandRepository)
    {
        $this->bandRepository = $bandRepository;
    }

    /**
     * @Route("/add", name="api_band_new", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {

        $name = $request->request->get("name");

        if (empty($name)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $band = new Band();
        $this->bandRepository->add($band->setName($name));
        return new JsonResponse(['message' => 'Band created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="api_band_get", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        if (empty($id)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        /** @var $band Band */
        $band = $this->bandRepository->findBandsById($id);

        return new JsonResponse($band, Response::HTTP_OK);
    }

    /**
     * @Route("/all", name="api_band_all", methods={"GET"})
     */
    public function all(): JsonResponse
    {
        /** @var $band Band */
        $bands = $this->bandRepository->findBands();

        return new JsonResponse($bands, Response::HTTP_OK);
    }

    /**
     * @Route("/remove/{band}", name="api_band_remove", methods={"DELETE"})
     */
    public function remove(Band $band): JsonResponse
    {


        $this->bandRepository->remove($band);

        return new JsonResponse(['message'=>"Band deleted!!!"], Response::HTTP_OK);
    }
}
