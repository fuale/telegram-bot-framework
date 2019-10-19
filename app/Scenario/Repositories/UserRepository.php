<?php declare(strict_types = 1);

namespace App\Scenario\Repositories;

use PhpOption\{None, Option, Some};
use RedBeanPHP\OODBBean;

class UserRepository extends AbstractRepository
{

    /**
     * @return \PhpOption\Option
     * @throws \RedBeanPHP\RedException
     */
    public function all(): Option
    {
        $users = $this->finder->find('user');
        return (bool) $users ? Some::create($users) : None::create();
    }

    /**
     * @param $id
     *
     * @return \PhpOption\Option
     * @throws \RedBeanPHP\RedException
     */
    public function one($id): Option
    {
        $user = $this->finder->findOne('user', 'id = ?', [$id]);
        return (bool) $user ? Some::create($user) : None::create();
    }
}