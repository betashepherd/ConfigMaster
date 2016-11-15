<?php

namespace ConfigMaster\DataStruct;

class LanCountry {
    
    public $id;
    
    public $name;

    public $desc;
    
    public $operator;
    
    public $timestamp;
    
	/**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

	/**
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

	/**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

	/**
     * @param field_type $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

	/**
     * @return the $desc
     */
    public function getDesc()
    {
        return $this->desc;
    }

	/**
     * @param field_type $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }
    
	/**
     * @return the $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

	/**
     * @param field_type $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

	/**
     * @return the $timestamp
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

	/**
     * @param field_type $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }


}
