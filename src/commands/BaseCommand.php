<?php
namespace ASPTest\commands;

use ASPTest\services\UserService;
use LogicException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class BaseCommand extends Command
{
    /** @var UserService */
    protected $userService = null;
    public function __construct(string $name = null)
    {
        $this->userService = new UserService();
        parent::__construct($name);
    }
    
}
