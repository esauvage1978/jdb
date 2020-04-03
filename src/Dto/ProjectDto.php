<?php

namespace App\Dto;

use App\Entity\Project;

class ProjectDto implements DtoInterface
{
    const FALSE='false';
    const TRUE='true';

    /**
     * @var ?string
     */
    private $wordSearch;


    /**
     * @var ?String
     */
    private $enable;

    /**
     * @var ?User
     */
    private $user;


    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return ProjectDto
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }




    /**
     * @return mixed
     */
    public function getWordSearch()
    {
        return $this->wordSearch;
    }

    /**
     * @param mixed $wordSearch
     * @return ProjectDto
     */
    public function setWordSearch($wordSearch)
    {
        $this->wordSearch = $wordSearch;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param mixed $enable
     * @return ProjectDto
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }
}
