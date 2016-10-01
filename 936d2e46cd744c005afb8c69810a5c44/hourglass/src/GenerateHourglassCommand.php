<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateHourglassCommand extends Command
{
    /**
     * Configure the command.
     *
     * @return Command
     */
    protected function configure()
    {
        $this->setName('draw')
             ->setDescription('Draw hourglass by height and capacity.');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return string
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $height = $this->promptHeight($input, $output);
        $capacity = $this->promptCapacity($input, $output);

        $hourglass = (new Hourglass($height, $capacity))->fillBulbs();

        $output->writeln("\nOutput:\n" . $hourglass);
    }

    /**
     * Prompt user for the height.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    private function promptHeight(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        while (true) {
            $question1 = new Question('Enter height (minimum-2): ', 2);
            $height = $helper->ask($input, $output, $question1);

            //check the height to ensure it's at least 2
            if ($height < 2) {
                $output->writeln('<error>Size must be at least 2. Please try again.</error>');
                continue;
            }

            break;
        }//while

        return $height;
    }

    /**
     * Prompt user for the capacity.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|float
     */
    private function promptCapacity(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        while (true) {
            $question2 = new Question('Enter capacity (1-100%): ', 1);
            $capacity = $helper->ask($input, $output, $question2);

            //check capacity to ensure if falls within 1-100 range
            if ($capacity < 1 || $capacity > 100) {
                $output->writeln('<error>Capacity must be in the 1-100% range. Please try again.</error>');
                continue;
            } else {
                $capacity = !is_float(floatval($capacity)) ? $capacity : round($capacity);
            }

            break;
        }//while

        return $capacity;
    }
}
