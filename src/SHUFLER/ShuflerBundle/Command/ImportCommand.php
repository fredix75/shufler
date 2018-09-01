<?php

namespace SHUFLER\ShuflerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use SHUFLER\ShuflerBundle\Entity\MusicTrack;

class ImportCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        // Name and description for app/console command
        $this
        ->setName('import:tracks:csv')
        ->setDescription('Import Tracks from CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
        
        // Importing CSV on DB via Doctrine ORM
        $import_status = $this->import($input, $output);
        
        if(!$import_status) $output->writeln('<comment>No file music.csv. Ciao bello!</comment>');
        
        // Showing when the script is over
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }
    
    protected function import(InputInterface $input, OutputInterface $output)
    {
        // Getting php array of data from CSV
        $data = $this->get();
        
        if(!$data) return;
        // Getting doctrine manager
        $em = $this->getContainer()->get('doctrine')->getManager();
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        
        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($data);
        $batchSize = 20;
        $i = 1;
        
        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();
        header('Content-type: text/html; charset=UTF-8');
        // Processing on each row of data
        foreach($data as $row) {
           $track = new MusicTrack();
           $track->setAuteur($row[0]);
           $track->setNumero($row[1]);
           $track->setTitre($row[2]);
           $track->setAnnee($row[3]);
           $track->setAlbum($row[4]);
           $track->setArtiste($row[5]);
           $track->setGenre($row[6]);
           $track->setPays($row[7]);
           $track->setDuree($row[8]);
           $track->setBitrate($row[9]);
           $track->setNote($row[10]);
	
			// Persisting the current user
            $em->persist($track);
            
			// Each 20 users persisted we flush everything
            if (($i % $batchSize) === 0) {

                $em->flush();
				// Detaches all objects from Doctrine for memory save
                $em->clear();
                
				// Advancing for progress display on console
                $progress->advance($batchSize);
				
                $now = new \DateTime();
                $output->writeln(' of tracks imported ... | ' . $now->format('d-m-Y G:i:s'));

            }

            $i++;

        }
		
		// Flushing and clear data on queue
        $em->flush();
        $em->clear();
		
		// Ending the progress bar process
        $progress->finish();
        
        return true;
    }

    protected function get() 
    {
        
        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert(';');
        
        return $data;
    }
}