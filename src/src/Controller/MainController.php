<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ParseService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller
 */
class MainController extends AbstractController
{
    /**
     * @Route("/tags/", name="parse_tags", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request, ParseService $parseService): JsonResponse
    {
        return new JsonResponse($parseService->parse($request));
    }

    /**
     * @Route("/tags/{id}", name="get_task", requirements={"id"="\d+"}, methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getTask(int $id, ParseService $parseService): JsonResponse
    {
        return new JsonResponse($parseService->get($id));
    }


}