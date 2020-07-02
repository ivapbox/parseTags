<?php


namespace App\Controller;


use App\Service\Parse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MainController
{
    /**
     * @var Parse
     */
    private $parse;

    /**
     * MainController constructor.
     * @param Parse $parse
     */
    public function __construct(Parse $parse)
    {
        $this->parse = $parse;
    }

    /**
     * @Route("/tags/", name="parse_tags")
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse($this->parse->parse($request));
    }

    /**
     * @Route("/tags/{id}", name="get_task", requirements={"id"="\d+"})
     * @param int $id
     * @return JsonResponse
     */
    public function getTask(int $id): JsonResponse
    {
        return new JsonResponse($this->parse->get($id));
    }


}