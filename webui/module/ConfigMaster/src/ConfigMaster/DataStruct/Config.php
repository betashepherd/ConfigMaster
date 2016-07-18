<?php

/**
 * config data struct
 */
namespace ConfigMaster\DataStruct;

class Config {
    
    /**
     * config id
     * @var string
     */
    public $id;
    
    /**
     * app id
     * @var string
     */
    public $appid;
    
    /**
     * config file path
     * @var string
     */
    public $path;
    
    /**
     * config key entrance
     * @var string
     */
    public $key;
    
    /**
     * config value
     * @var string
     */
    public $value;
    
    /**
     * config version
     * @var number
     */
    public $version;
    
    /**
     * update time
     * @var number
     */
    public $timestamp;
    
    /**
     * operator
     * @var string
     */
    public $operator;
    
    /**
     * op desc
     * @var string
     */
    public $desc;
    
    /**
     * config status
     * @var number
     */
    public $status;
    
    
	/**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

	/**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

	/**
     * @return the $appid
     */
    public function getAppid()
    {
        return $this->appid;
    }

	/**
     * @param string $appid
     */
    public function setAppid($appid)
    {
        $this->appid = $appid;
    }

	/**
     * @return the $path
     */
    public function getPath()
    {
        return $this->path;
    }

	/**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

	/**
     * @return the $key
     */
    public function getKey()
    {
        return $this->key;
    }

	/**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

	/**
     * @return the $value
     */
    public function getValue()
    {
        return $this->value;
    }

	/**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

	/**
     * @return the $version
     */
    public function getVersion()
    {
        return $this->version;
    }

	/**
     * @param number $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

	/**
     * @return the $timestamp
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

	/**
     * @param number $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

	/**
     * @return the $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

	/**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

	/**
     * @return the $desc
     */
    public function getDesc()
    {
        return $this->desc;
    }

	/**
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

	/**
     * @param number $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

	public function __unset($property) {
        unset($this->$property);
    }
}