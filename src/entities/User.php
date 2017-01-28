<?php
namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="user")
 * @Entity(repositoryClass="App\Repository\UserRepository")
 **/
class User
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     **/
    protected $id;


    /**
     * @Column(type="string", length=32, unique=true, nullable=false)
     */
    protected $username;


    /**
     * @Column(type="string", length=64, nullable=false)
     **/
    protected $password;

    /**
     * @Column(type="string", length=12, nullable=false)
     */
    protected $role;


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

    public function __construct() {
        $this->trips = new ArrayCollection();
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
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'role' => $this->getRole(),
            'created' => $this->getCreated() ? $this->getCreated()->format('Y-m-d H:i:s') : null ,
            'updated' => $this->getUpdated() ? $this->getUpdated()->format('Y-m-d H:i:s') : null ,
        ];
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     * @return User
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
     * @return User
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



}
