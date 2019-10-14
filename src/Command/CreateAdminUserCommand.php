<?php

namespace App\Command;

use App\Entity\User;
use App\Helper\Orm;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateAdminUserCommand extends Command
{
    private $orm;
    private $passwordEncoder;

    protected static $defaultName = 'app:create-admin-user';

    public function __construct(Orm $orm, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->orm = $orm;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Create New Admin User')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $orm = $this->orm;

        $io->title($this->getDescription());
        $helper = $this->getHelper('question');
        $question = new Question('What is the email for the new user?');
        $email = $helper->ask($input, $output, $question);

        $helper = $this->getHelper('question');
        $question = new Question('What is the password for new user?');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $question);

        $errors = [];
        if($email === null) {
            $errors[] = 'Email cannot be blank.';
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL) and $email !== null) {
            $errors[] = 'Not a valid email.';
        }
        if($password === null) {
            $errors[] = 'Password cannot be blank.';
        }
        if(strlen($password) < 6 and $password !== null) {
            $errors[] = 'Password must be longer than 6 characters.';
        }

        if((!preg_match('@[A-Z]@', $password) || !preg_match('@[a-z]@', $password) || !preg_match('@[0-9]@', $password)) and strlen($password) > 5 and $password !== null) {
            $errors[] = 'Password must contain at least one number and one uppercase.';
        }

        foreach ($errors as $error) {
            $io->caution($error);
        }

        if(count($errors) > 0) {
            $io->error('Run Command Again');
            return;
        }

        $user = new User($email);

        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $password
            )
        );
        $user->setRoles([User::ROLE_ADMIN]);
        $orm->persist($user);
        $orm->flush();
        $io->success(sprintf('User Created: %s', $user->getEmail()));
        return;
    }
}
