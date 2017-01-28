<?php
namespace App\Entities;


/**
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="answer")
 * @Entity(repositoryClass="App\Repository\AnswerRepository")
 **/
class Answer
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $id;


    /**
     * @ManyToOne(targetEntity="App\Entities\Question", inversedBy="answers")
     * @JoinColumn(name="question_id", referencedColumnName="id")
     */
    protected $question;

    /**
     * @Column(type="integer", name="question_id")
     */
    protected $questionId;

    /**
     * @Column(type="string", length=1024, unique=false, nullable=false)
     */
    protected $text;


    /**
     * @Column(type="integer", nullable=false)
     */
    protected $number = 0;


    /**
     * @Column(type="boolean", unique=false, nullable=false)
     */
    protected $correct;


    /**
     * @var datetime $created
     *
     * @Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Column(type="datetime", nullable = true)
     */
    protected $updated;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Trip
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param datetime $created
     * @return Trip
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param datetime $updated
     * @return Trip
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }



    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'correct' => $this->getCorrect(),
            'questionId' => $this->getQuestionId(),
            'created' => $this->getCreated() ? $this->getCreated()->format('Y-m-d H:i:s') : null ,
            'updated' => $this->getUpdated() ? $this->getUpdated()->format('Y-m-d H:i:s') : null ,
        ];
    }

    /**
     * Gets triggered only on insert

     * @PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update

     * @PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * @param mixed $correct
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return mixed
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * @param mixed $questionId
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

}
