<?php
namespace App\Services;

use App\Entities\Answer;
use App\Entities\Question;
use App\Entities\Trip;
use Exception;
use InvalidArgumentException;
use Slim\Container;

class QuestionService
{

    /*
     * @var EntityManager
     */
    protected $em;

    /*
     * @var AnswerRepository
     */
    protected $answerRepo;

    /*
     * @var QuestionRepository
     */
    protected $questionRepo;


    public function __construct(Container $c)
    {
        $this->em = $c->get('em');
        $this->answerRepo = $c->get('answerRepository');
        $this->questionRepo = $c->get('questionRepository');
    }

    /**
     * @param $data
     * @return question
     * @throws Exception
     */
    public function create($data)
    {
        if (!empty($data['id'])) {
            throw new \InvalidArgumentException('Invalid id param.');
        }
        $this->validData($data);

        $exam = $this->em->getReference('App\Entities\Exam',$data['examId']);

        $question = new Question();
        $question
            ->setExam($exam)
            ->setText($data['text']);

        $answers = $this->recreateAnswers($question, $data['answers']);
        $this->em->persist($question);
        array_map([$this->em, 'persist'], $answers);
        $this->em->flush();
        return $question;
    }

    protected function recreateAnswers($question, $answersData)
    {
        $answers = [];
        $number = 1;
        foreach($answersData as $answerData) {
            $answer = new Answer();
            $answer->setNumber($number++);
            $answer->setCorrect(!empty($answerData['correct']));
            $answer->setText($answerData['text']);
            $answer->setQuestion($question);
            $answers[] = $answer;
        }
        return $answers;
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

        $question = $this->find($data['id']);

        if (empty($question)) {
            throw new Exception('Not found');
        }

        $exam = $question->getExam();
        if ($userId && $exam && $userId != $exam->getUser()->getId()) {
            throw new Exception('Not authorized operation');
        }

        if (isset($data['text'])) {
            $question->setText($data['text']);
        }

        if (isset($data['number'])) {
            $question->setNumber($data['number']);
        }

        $this->deleteAnswersForQuestion($question);
        $answers = $this->recreateAnswers($question, $data['answers']);
        $this->em->persist($question);
        array_map([$this->em, 'persist'], $answers);

        $this->em->flush();
        return $question;
    }

    public function deleteAnswersForQuestion(Question $question)
    {
        $answers = $this->answerRepo->findBy(['question' => $question]);
        array_map([$this->em, 'remove'], $answers);
    }

    public function delete($id, $userId = false)
    {
        $question = $this->find($id);
        if (empty($question)) {
            throw new \Exception('No question found.');
        }
        if ($userId && $question->getUser()->getId() != $userId) {
            throw new \Exception('You are not allowed to delete this question.');
        }
        $this->em->remove($question);
        $this->em->flush($question);
    }

    /**
     * @param $id
     * @return Question
     */
    public function find($id)
    {
        $question = $this->questionRepo->find($id);
        return $question;
    }

    protected function validData(&$data)
    {
        if (empty($data['text'])) {
            throw new InvalidArgumentException('Missing text param.');
        }
        if (empty($data['examId'])) {
            throw new InvalidArgumentException('Missing examId param.');
        }
        if (empty($data['answers'])) {
            throw new InvalidArgumentException('Missing answers.');
        }
    }



}