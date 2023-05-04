<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'secret:regenerate-app-secret',
    description: 'Regenerate random value and update APP_SECRET',
)]
class RegenerateAppSecretCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('env-file', InputArgument::OPTIONAL, 'env file {.env, .env.local.php}', '.env');
        $this->addArgument('length', InputArgument::OPTIONAL, 'Length of the new secret', 16);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $envFileName = $input->getArgument('env-file');
        $length = $input->getArgument('length');

        if ($envFileName === '.env') {
            $secret = bin2hex(random_bytes($length));
            $filepath = __DIR__ . '/../../' . $envFileName;
            $io->note(sprintf('Regenerating APP_SECRET in %s', $filepath));

            $env = file_get_contents($filepath);
            if (preg_match('/^APP_SECRET=.*$/m', $env)) {
                $env = preg_replace('/^APP_SECRET=.*$/m', sprintf('APP_SECRET=%s', $secret), $env);
            } else {
                $env .= sprintf('APP_SECRET=%s', $secret);
            }
            file_put_contents($filepath, $env);

            $io->success(sprintf('APP_SECRET=%s', $secret));
            return Command::SUCCESS;
        } elseif ($envFileName === '.env.local.php') {
            $secret = bin2hex(random_bytes($length));
            $filepath = __DIR__ . '/../../' . $envFileName;
            $io->note(sprintf('Regenerating APP_SECRET in %s', $filepath));
            $env = file_get_contents($filepath);
            if (preg_match('/^.*APP_SECRET.*$/m', $env)) {
                $env = preg_replace('/^.*APP_SECRET.*$/m', sprintf('    \'APP_SECRET\' => \'%s\',', $secret), $env);
            } else {
                $env = preg_replace('/^.*return array\($/m', sprintf('return array(
    \'APP_SECRET\' => \'%s\',', $secret), $env);

            }
            file_put_contents($filepath, $env);

            $io->success(sprintf('APP_SECRET=%s', $secret));
            return Command::SUCCESS;
        } else {
            $io->error(sprintf('Invalid env file: %s', $envFileName));
            return Command::FAILURE;
        }
    }
}
