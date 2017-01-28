<?php
namespace App\Services;

use App\Entities\Exam;
use Exception;
use InvalidArgumentException;
use Slim\Container;

class ExamService
{

    /*
     * @var EntityManager
     */
    protected $em;

    /*
     * @var ExamRepository
     */
    protected $examRepo;

    public function __construct(Container $c)
    {
        $this->em = $c->get('em');
        $this->examRepo = $c->get('examRepository');
    }


    /**
     * @param $data
     * @return exam
     * @throws Exception
     */
    public function create($data)
    {
        if (!empty($data['id'])) {
            throw new \InvalidArgumentException('Invalid id param.');
        }
        $this->validData($data);

        $exam = new Exam();
        $exam
            ->setUser($this->em->getReference('App\Entities\User', $data['userId']))
            ->setName($data['name'])
            ->setPublished($this->string2date($data['published']));

        $this->em->persist($exam);
        $this->em->flush();
        return $exam;
    }

    protected function string2date($string)
    {
        $date = new \DateTime($string);
        return $date;
    }

    public function update($data, $userId = false)
    {
        if (empty($data['id'])) {
            throw new InvalidArgumentException('Missing id param.');
        }

        $exam = $this->find($data['id']);
        if ($userId && $exam && $userId !== $exam->getUser()->getId()) {
            throw new Exception('Not authorized operation');
        }
        if (empty($exam)) {
            throw new Exception('Not found');
        }

        if (isset($data['name'])) {
            $exam->setName($data['name']);
        }

        if (isset($data['published'])) {
            $exam->setPublished($this->string2date($data['published']));
        }
        $this->em->persist($exam);
        $this->em->flush();
        return $exam;
    }

    public function delete($id, $userId = false)
    {
        $exam = $this->find($id);
        if (empty($exam)) {
            throw new \Exception('No exam found.');
        }
        if ($userId && $exam->getUser()->getId() != $userId) {
            throw new \Exception('You are not allowed to delete this exam.');
        }
        $this->em->remove($exam);
        $this->em->flush($exam);
    }

    public function find($id, $userId = null)
    {
        $exam = $this->examRepo->find($id);
        if ($exam && $userId && $exam->getUser()->getId() !== $userId) {
            return null;
        }
        return $exam;
    }

    public function findAll($userId = null)
    {
        if ($userId) {
            $user = $this->em->getReference('App\Entities\User', $userId);
            $all = $this->examRepo->findBy(['user' => $user], ['created' => 'asc']);
        } else {
            $all = $this->examRepo->findBy([], ['created' => 'asc']);
        }

        return array_map(function ($exam) {
            return $exam->toArray();
        }, $all);
    }

    protected function validData(&$data)
    {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Missing name param.');
        }
        if (empty($data['userId'])) {
            throw new InvalidArgumentException('Missing userId param.');
        }
        if (empty($data['published'])) {
            throw new InvalidArgumentException('Missing published param.');
        }
    }


}