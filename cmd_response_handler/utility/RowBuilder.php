<?php

/**
 * Description of RowBuilder
 *
 * @author Xavier García <xaviergaro.dev@gmail.com>
 */
class RowBuilder
{

    private $id;
    private $title;
    private $priority;
    private $groups = [];

    public function __construct($id = '', $title = '', $priority = 0)
    {
        $this->setId($id)
            ->setTitle($title)
            ->setPriority($priority);
    }

    public function build()
    {

        $row['id'] = $this->id;
        $row['title'] = $this->title;
        $row['groups'] = $this->groups;

        return $row;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }


    public function addGroup($group)
    {
        $this->groups[] = $group;
        return $this;
    }

    public function addGroups(array $groups)
    {
        for ($i = 0, $len = count($groups); $i < $len; $i++) {
            $this->addGroup($groups[$i]);
        }
        return $this;
    }

}