<?php

namespace ASPTest\commands;

use LogicException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserPasswordCommand extends BaseCommand
{
    public function configure()
    {
        $this
            ->setName('USER:CREATE-PWD')
            ->setDescription("Command used to set a password in a existing user")
            ->addArgument('id', InputArgument::REQUIRED, 'User ID');
    }
    /**
     * @param InputInterface $inputInterface
     * @param OutputInterface $outputInterface
     * @param string $question
     * @param string|null $passwordStep1
     * @return void
     */
    protected function askPassword(InputInterface $inputInterface, OutputInterface $outputInterface, $question, $passwordStep1 = null)
    {
        /** @var QuestionHelper */
        $questionService = $this->getHelper('question');
        return $questionService->ask($inputInterface, $outputInterface, (new Question($question))
            ->setHidden(true)
            ->setValidator(function ($value) use ($passwordStep1) {
                if (empty(trim($value))) {
                    throw new LogicException("The password is required");
                }
                $errors = $this->userService->validatePassword($value, $passwordStep1);
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
    public function execute(InputInterface $inputInterface, OutputInterface $outputInterface)
    {
        $firstStepPassword = $this->askPassword($inputInterface, $outputInterface, "Please set a password for the user (The password will hidden) : ");
        $this->askPassword($inputInterface, $outputInterface, "Please confirm a password for the user (The password will hidden) : ", $firstStepPassword);
        if ($this->userService->update($inputInterface->getArgument('id'), [
            'password' => $firstStepPassword,
        ])) {
            $outputInterface->writeln("<info>The user was updated successfully;</info>");
            return Command::SUCCESS;
        };
        $outputInterface->writeln([
            "<error>Error has ocurred during the creation process</error>",
        ]);
        return Command::FAILURE;
    }
}
