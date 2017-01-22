<?php
namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="question")
 **/
class Question
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $id;


    /**
     * @OneToMany(targetEntity="Answer", mappedBy="question", cascade={"remove"})
     */
    private $answers;

    /**
     * @ManyToOne(targetEntity="Exam", inversedBy="questions")
     * @JoinColumn(name="exam_id", referencedColumnName="id")
     */
    protected $exam;

    /**
     * @Column(type="integer", nullable=false)
     */
    protected $number = 0;

    /**
     * @Column(type="string", length=1024, unique=false, nullable=false)
     */
    protected $text;



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



    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

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
            'answers' => $this->getAnswersArray(),
            'text' => $this->getText(),
            'created' => $this->getCreated() ? $this->getCreated()->format('Y-m-d H:i:s') : null ,
            'updated' => $this->getUpdated() ? $this->getUpdated()->format('Y-m-d H:i:s') : null ,
        ];
    }

    public function getAnswersArray()
    {
        $answers = [];
        foreach($this->getAnswers() as $answer) {
            $answers[] = $answer->toArray();
        }
        return $answers;
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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param mixed $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * @param mixed $exam
     */
    public function setExam($exam)
    {
        $this->exam = $exam;
        return $this;
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
        return $this;
    }

}
