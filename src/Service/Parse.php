<?php


namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class Parse
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * Parse constructor.
     * @param EntityManagerInterface $em
     * @param TaskRepository $taskRepository
     */
    public function __construct(EntityManagerInterface $em, TaskRepository $taskRepository)
    {
        $this->em = $em;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function parse(Request $request): array
    {
        $url = $request->get('url');
        if ($this->validUrl($url)) {
            $task = $this->taskRepository->findOneBy(['url' => $url]);
            if ($task === null) {
                $task = new Task();
                $task->setStatus('new');
                $task->setUrl($url);
                $this->em->flush();
                $this->action($task);
                return ['id' => $task->getId()];
            } else {
                return ['error' => 'Такой URL уже есть в базе'];
            }
        } else {
            return ['error' => 'Невалидный URL'];
        }
    }

    /**
     * @param string $url
     * @return bool
     */
    private function validUrl(string $url): bool
    {
        $file_headers = get_headers($url);
        if ($file_headers === false) return false;
        $code = 0;
        foreach ($file_headers as $header) {
            if (preg_match("/^Location: (http.+)$/", $header, $m)) $url = $m[1];
            if (preg_match("/^HTTP.+\s(\d\d\d)\s/", $header, $m)) $code = $m[1];
        }
        if ($code == 200) return true;
        else return false;
    }

    /**
     * @param Task $task
     */
    private function action(Task &$task)
    {
        $task->setStatus('process');
        $this->em->flush();
        $response = [];
        $out = file_get_contents($task->getUrl());
        preg_match_all('!<([\w]+)!si', $out, $data);
        $data = $data[1] ?? [];
        foreach ($data as $datum) {
            $response[$datum] = isset($response[$datum]) ? $response[$datum] + 1 : 1;
        }
        $task->setTags($response);
        $task->setStatus('finished');
        $this->em->flush();
    }

    /**
     * @param int $id
     * @return array
     */
    public function get(int $id): array
    {
        $task = $this->taskRepository->find($id);
        return $task->getStatus() === 'finished' ? $task->getTags() : ['error' => 'Задание еще не выполнено'];
    }
}