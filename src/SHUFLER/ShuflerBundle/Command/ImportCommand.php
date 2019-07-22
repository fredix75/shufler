<?php
namespace SHUFLER\ShuflerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use SHUFLER\ShuflerBundle\Entity\MusicTrack;
use SHUFLER\ShuflerBundle\Entity\Artiste;
use SHUFLER\ShuflerBundle\Entity\Album;

class ImportCommand extends ContainerAwareCommand
{

    const BATCH_SIZE = 20;

    const YOUTUBE_URL = '?part=snippet&maxResults=5&format=json&q=';

    private $artistes = [];

    private $albums = [];

    protected function configure()
    {
        // Name and description for app/console command
        $this->setName('import:tracks:csv')->setDescription('Import Tracks from CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
        
        $output->writeln('<comment>Searchin\' File music.csv</comment>');
        
        // Importing CSV on DB via Doctrine ORM
        $output->writeln('<comment>Step 1. Importing Tracks</comment>');
        $import_status = $this->importTracks($input, $output);
        
        // Importing Artistes from Tracks via Doctrine ORM
        if ($import_status) {
            $output->writeln('<comment>Step 2. Importing Artists</comment>');
            $import_status2 = $this->importArtists($input, $output);
        } else {
            $output->writeln('<comment>File Trouble Ciao bello!</comment>');
        }
        
        if ($import_status2) {
            $output->writeln('<comment>Step 3. Importing Albums</comment>');
            $import_status3 = $this->importAlbums($input, $output);
        } else {
            $output->writeln('<comment>No Artists in Tracks File. Ciao bello!</comment>');
        }
        
        if (! $import_status3) {
            $output->writeln('<comment>No Albums in Tracks File. Ciao bello!</comment>');
        }
        
        // Showing when the script is over
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    protected function importTracks(InputInterface $input, OutputInterface $output)
    {
        // Getting php array of data from CSV
        $data = $this->get();
        
        if (! $data)
            return;
        // Getting doctrine manager
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();
        
        // TRUNCATE INITIAL DB -- désactivé pour le moment
        $query = 'TRUNCATE music_track;';
       /* $em->getConnection()
            ->prepare($query)
            ->execute();
        */
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()
            ->getConfiguration()
            ->setSQLLogger(null);
        
        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($data);
        $i = 1;
        
        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();
        
        header('Content-type: text/html; charset=UTF-8');
        // Processing on each row of data
        foreach ($data as $row) {
                       
            $track = new MusicTrack();
            $track->setAuteur($row[0]);
            $track->setNumero($row[1]);
            $track->setTitre($row[2]);
            $row[3] = ($row[3] * 1 == 0) ? '' : $row[3];
            $track->setAnnee($row[3]);
            $track->setAlbum($row[4]);
            $track->setArtiste($row[5]);
            $track->setGenre($row[6]);
            $row[7] = (trim($row[7]) == 'Éditeur Inconnu') ? '' : $row[7];
            $track->setPays($row[7]);
            $track->setDuree($row[8]);
            $track->setBitrate($row[9]);
            $track->setNote($row[10]);
            //if($track->getNote() == 5) {
            if($track->getNote() == 5) {
                try {
                    $search = str_replace(' ', '%20', $row[0] . ' ' . $row[2]);
                    $url = $this->getContainer()->getParameter('youtube_api_search_url') . self::YOUTUBE_URL . $search . '&key=' . $this->getContainer()->getParameter('youtube_key') . '&type=videos';
                    $curl = $this->getContainer()->get('get.curl')->getInit($url);
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $value = json_decode($response);
                    $track->setKey($value->items[0]->id->videoId);
                } catch (\Exception $e) {
    
                }
            }
            
            $em->persist($track);
            
            if (empty($this->artistes[$row[0]])) {
                $this->artistes[$row[0]] = 1;
            }
            
            if (empty($this->albums[$row[5]][$row[4]])) {
                $this->albums[$row[5]][$row[4]] = [
                    "genre" => $row[6],
                    "annee" => $row[3]
                ];
            }
            
            // Each 20 users persisted we flush everything
            if (($i % self::BATCH_SIZE) === 0) {
                
                $em->flush();
                $em->clear();
                 
                $progress->advance(self::BATCH_SIZE);
                
                $now = new \DateTime();
                $output->writeln(' of tracks imported ... | ' . $now->format('d-m-Y G:i:s'));
            }
           
            $i ++;
        }

        $em->flush();
        $em->clear();
        
        $progress->finish();
        
        return true;
    }

    protected function importArtists(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();
        
        // TRUNCATE INITIAL DB
        $query = 'TRUNCATE artiste;';
        /*
        $em->getConnection()
            ->prepare($query)
            ->execute();
        */
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()
            ->getConfiguration()
            ->setSQLLogger(null);
        
        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($this->artistes);
        $i = 1;
        
        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();
        foreach ($this->artistes as $artisteName => $value) {
           
            $artiste = new Artiste();
            $artiste->setName($artisteName);
            try {             
                $url = $this->getContainer()->getParameter('lastfm_api_url') . '?method=artist.getInfo&format=json&api_key=' . $this->getContainer()->getParameter('lastfm_key') . '&artist=' . $artisteName;
                $curl = $this->getContainer()->get('get.curl')->getInit($url);
                $response = json_decode(curl_exec($curl));
                curl_close($curl);
                
                $artiste->setImageUrl($response->artist->image[4]->{'#text'});
                $artiste->setBio($response->artist->bio->content);
            } catch(\Exception $e) {

            }
            
            $em->persist($artiste);
            $em->flush();
            
            // Each 20 users persisted we flush everything
            if (($i % self::BATCH_SIZE) === 0) {
                
                $em->flush();
                // Detaches all objects from Doctrine for memory save
                $em->clear();
                
                // Advancing for progress display on console
                $progress->advance(self::BATCH_SIZE);
                
                $now = new \DateTime();
                $output->writeln(' of artists imported ... | ' . $now->format('d-m-Y G:i:s'));
            }
            
            $i ++;
        }
        // Flushing and clear data on queue
        $em->flush();
        $em->clear();
        
        // Ending the progress bar process
        $progress->finish();
        
        return true;
    }

    protected function importAlbums(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();
        
        // TRUNCATE INITIAL DB
        $query = 'TRUNCATE album;';
        /*
        $em->getConnection()
            ->prepare($query)
            ->execute();
        */
        
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()
            ->getConfiguration()
            ->setSQLLogger(null);
        
        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($this->albums);
        $i = 1;
        
        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();
        foreach ($this->albums as $artisteName => $albums) {
            foreach ($albums as $albumName => $features) {
                $album = new Album();
                $album->setAuteur($artisteName);
                $album->setName($albumName);
                $album->setAnnee($features['annee']);
                $album->setGenre($features['genre']);
                
                if(strtolower($albumName) != 'divers' && strtolower($artisteName) != 'divers') {
                    $search = $artisteName . " " . $albumName;
                    $search = str_replace(" ", "%20", $search);
                    $url = $this->getContainer()->getParameter('youtube_api_search_url') . self::YOUTUBE_URL . $search . '&key=' . $this->getContainer()->getParameter('youtube_key') . '&type=playlist';

                    try {
                        $curl = $this->getContainer()->get('get.curl')->getInit($url);
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $value = json_decode($response);
                        $album->setYoutubeKey($value->items[0]->id->playlistId);
                    } catch (\Exception $e) {
                        
                    }
                }

                $em->persist($album);
                $em->flush();
                // Each 20 users persisted we flush everything
                if (($i % self::BATCH_SIZE) === 0) {
                    
                    $em->flush();
                    // Detaches all objects from Doctrine for memory save
                    $em->clear();
                    
                    // Advancing for progress display on console
                    $progress->advance(self::BATCH_SIZE);
                    
                    $now = new \DateTime();
                    $output->writeln(' of albums imported ... | ' . $now->format('d-m-Y G:i:s'));
                }
                
                $i ++;
            }
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