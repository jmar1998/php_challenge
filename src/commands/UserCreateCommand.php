<?php

namespace ASPTest\commands;

use ASPTest\components\database\DbComponent;
use ASPTest\services\UserService;
use LogicException;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserCreateCommand extends BaseCommand
{
    public function configure()
    {
        return $this
            ->setName('USER:CREATE')
            ->setDescription("Command used to create a new user");
    }
    /**
     * @param InputInterface $inputInterface
     * @param OutputInterface $outputInterface
     * @param string $question
     * @param [type] $attribute
     * @param boolean $required
     * @return void
     */
    protected function ask(InputInterface $inputInterface, OutputInterface $outputInterface, string $question, $attribute, bool $required = true)
    {
        /** @var QuestionHelper */
        $questionService = $this->getHelper('question');
        return $questionService->ask($inputInterface, $outputInterface, (new Question($question))
            ->setValidator(function ($value) use ($required, $attribute) {
                if (empty(trim($value)) && $required) {
                    throw new LogicException("The $attribute is required");
                }
                $errors = $this->userService->validateAttibutes([
                    $attribute => $value
                ], [
                    'skipOnUndefined' => true
                ]);
                if (!empty($errors)) {
                    throw new LogicException(implode("\n", $errors));
                }
                return empty($value) ? null : $value;
            }));
    }
    /**
     * @param InputInterface $inputInterface
     * @param OutputInterface $outputInterface
     * @return void
     */
    protected function execute(InputInterface $inputInterface, OutputInterface $outputInterface)
    {
        $arguments = [
            'firstName' => $this->ask($inputInterface, $outputInterface, "What its the user name? : ", 'firstName'),
            'lastName' => $this->ask($inputInterface, $outputInterface, "What its the last name? : ", 'lastName'),
            'email' => $this->ask($inputInterface, $outputInterface, "What its the email? : ", 'email'),
            'age' => $this->ask($inputInterface, $outputInterface, "What its the age? : ", 'age', false),
        ];
        if ($user = $this->userService->create($arguments)) {
            $outputInterface->writeln([
                "<info>The user was created successfully :</info>",
                "=====================================",
                json_encode($user, JSON_PRETTY_PRINT)
            ]);
            return Command::SUCCESS;
        }
        $outputInterface->writeln([
            "<error>Error has ocurred during the creation process</error>",
        ]);
        return Command::FAILURE;
    }
}
