<?php
namespace App\Controllers;

use Psr\Log\InvalidArgumentException;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class QuestionController extends BaseController
{

    /*
     * If the user is not admin returns the user id
     * of the logged user to filter the records.
     */
    protected function getFilterId()
    {
        $user = $this->get('userSession')->getUser();
        $userId = false;
        if ($user['role'] !== 'admin') {
            $userId = $user['id'];
        }
        return $userId;
    }

    protected function getLoggedId()
    {
        $user = $this->get('userSession')->getUser();
        if (empty($user)) {
            throw new InvalidArgumentException('Invalid state app. No user logged');
        }
        return $user['id'];
    }

    public function collection(Request $req, Response $res)
    {
        $this->logger->info("List questions");
        $questions = $this->get('questionService')->findAll($this->getFilterId());
        return $res->withJson($questions);
    }

    public function create(Request $req, Response $res)
    {
        $questionService = $this->get('questionService');
        $params = $req->getParams();
        $id = $this->getFilterId();
        if ($id) {
            $params['userId'] = $id;
        }
        if(empty($params['userId'])) {
            $params['userId'] = $this->getLoggedId();
        }
        $question = $questionService->create($params);
        return $res->withJson($question->toArray());
    }

    public function view(Request $req, Response $res, $args)
    {
        $questionService = $this->get('questionService');
        $this->logger->info("Get question" . $args['id']);

        $question = $questionService->find($args['id'], $this->getFilterId());
        if (empty($question)) {
            throw new \Exception('No question.');
        }
        return $res->withJson($question->toArray());
    }

    public function modify(Request $req, Response $res, $args)
    {
        $questionService = $this->get('questionService');
        $this->logger->info("Put question");
        $id = $this->getFilterId();
        $params = $req->getParams();
        if ($id) {
            $params['userId'] = $id;
        }
        $params['id'] = $args['id'];

        $question = $questionService->update($params, $params['userId']);
        if (empty($question)) {
            throw new NotFoundException('No question.');
        }
        return $res->withJson($question->toArray());
    }

    public function delete(Request $req, Response $res, $args)
    {
        $questionService = $this->get('questionService');
        $this->logger->info("Delete question route");
        $questionService->delete($args['id'], $this->getFilterId());
    }
}