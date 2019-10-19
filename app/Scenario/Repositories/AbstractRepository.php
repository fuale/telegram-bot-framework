<?php declare(strict_types = 1);

namespace App\Scenario\Repositories;

use RedBeanPHP\Finder;
use RedBeanPHP\OODB;
use RedBeanPHP\OODBBean;

abstract class AbstractRepository
{

    /** @var \RedBeanPHP\Finder */
    protected $finder;

    /** @var \RedBeanPHP\OODB */
    protected $writer;

    /**
     * @param $custom_time
     *
     * @return string
     * @throws \Exception
     */
    protected function getTimestamp($custom_time = null): string
    {
        return (new \DateTimeImmutable($custom_time ?: time()))->format('Y-m-d H:i:s');
    }

    public function __construct(Finder $finder, OODB $writer)
    {
        $this->finder = $finder;
        $this->writer = $writer;
    }

    /**
     * @param OODBBean[]|OODBBean $oodbean
     *
     * @return int|null|array saved ids
     * @throws \RedBeanPHP\RedException
     */
    public function save($oodbean)
    {
        if (\is_array($oodbean)) {
            foreach ($oodbean as $bean) {
                $id[] = $this->writer->store($bean);
            }

            return $id ?? [];
        }

        if ($oodbean instanceof OODBBean) {
            return $this->writer->store($oodbean);
        }

        throw new \RuntimeException('Getting invalid object to store');
    }

}