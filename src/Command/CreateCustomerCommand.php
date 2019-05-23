<?php

namespace App\Command;

use App\Entity\Customer as User;
use App\Repository\CustomerRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class CreateCustomerCommand extends Command
{
    protected static $defaultName = 'app:create-customer';

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var CustomerRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, CustomerRepository $repository)
    {
        parent::__construct();
        $this->encoderFactory = $encoderFactory;
        $this->repository = $repository;
    }

    protected function configure()
    {
        $this
            ->setName('fos:user:create')
            ->setDescription('Create a user.')
            ->setDefinition(
                [
                    new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                    new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                ]
            )
            ->setHelp(
                <<<'EOT'
The <info>%command.name%</info> command creates a user:

  <info>php %command.full_name% bryan</info>

This interactive shell will ask you for an username and then a password.

You can alternatively specify the username and password as the second and third arguments:

  <info>php %command.full_name% bryan bryan@example.com mypassword</info>


EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $rawPassword = $input->getArgument('password');
        $encodedPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword($rawPassword, null);

        $user = new User();
        $user->setEmail($username);
        $user->setPassword($encodedPassword);

        $this->repository->save($user);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('username')) {
            $question = new Question('Please enter your username:');
            $question->setValidator(
                function ($username) {
                    if (empty($username)) {
                        throw new \Exception('username can not be empty');
                    }

                    return $username;
                }
            );
            $questions['username'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(
                function ($password) {
                    if (empty($password)) {
                        throw new \Exception('Password can not be empty');
                    }

                    return $password;
                }
            );
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
