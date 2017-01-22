<?php
namespace App\Entities;


/**
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="exam")
 **/
class Exam
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $id;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @OneToMany(targetEntity="Question", mappedBy="exam", cascade={"remove"})
     */
    private $questions;

    /**
     * @Column(type="string", length=255, unique=false, nullable=false)
     */
    protected $name;

    /**
     * @Column(type="datetime", nullable=false)
     */
    protected $published;


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
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    public function toArray($withQuestions = false)
    {
        $ret = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'published' => $this->getPublished() ? $this->getPublished()->format('Y-m-d H:i:s') : null ,
            'created' => $this->getCreated() ? $this->getCreated()->format('Y-m-d H:i:s') : null ,
            'updated' => $this->getUpdated() ? $this->getUpdated()->format('Y-m-d H:i:s') : null ,
        ];
        if ($withQuestions) {
            $ret['questions'] = $this->getQuestionsArray();
        }
        return $ret;
    }

    public function getQuestionsArray()
    {
        $questions = [];
        foreach($this->getQuestions() as $question) {
            $questions[] = $question->toArray();
        }
        return $questions;
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}
