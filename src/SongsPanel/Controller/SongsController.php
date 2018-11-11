<?php
namespace App\SongsPanel\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\SongsPanel\DataAccess\SongsList;
use App\SongsPanel\Form\SongForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\SongsPanel\Model\Song;
use App\SongsPanel\DataAccess\PlayerClient;

/**
 * Description of SonglsController
 *
 * @author marek
 */
class SongsController extends AbstractController
{

    private $songsList;
    
    private $player;

    public function __construct(
        SongsList $songsDao,
        PlayerClient $player 
    ) {
        $this->songsList = $songsDao;
        $this->player = $player;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $this->player->screenshot();
        $form = $this->createForm(SongForm::class, null, [
                    'action' => $this->generateUrl('add_song')
                ])->createView();

        return $this->render('index.html.twig', [
                    'songs' => $this->songsList->findAll(),
                    'form' => $form
        ]);
    }

    /**
     * @Route("/songs/{id}/start", name="start_song")
     */
    public function startSong($id)
    {
        $this->songsList->load();
        
        $currentlyPlayingSong = $this->songsList->findPlaying();
        
        if ($currentlyPlayingSong != null) {
            $currentlyPlayingSong->stop();
        }
        
        $songToPlay = $this->songsList->findById($id);
        
        $this->player->play($songToPlay->url());
        
        $songToPlay->play();
        $this->songsList->persist();
        return $this->redirectToRoute("index");
    }
   
    /**
     * @Route("/screenshot")
     */
    public function screenshot() {
        $this->player->screenshot();
        
        return new Response("OK");
    }

    /**
     * 
     * @Route("/songs/{id}/end", name="end_song")
     */
    public function endSong($id)
    {
        $this->songsList->load();
        $this->songsList->findById($id)->stop();
        
        $this->player->stop();
        
        $this->songsList->persist();
        return $this->redirectToRoute("index");
    }

    /**
     * @Route("/songs/add", name="add_song")
     */
    public function addSong(Request $request)
    {

        $form = $this->createForm(SongForm::class);
        $form->handleRequest($request);
        
        if (!$form->isValid() || !$form->isSubmitted()) {
            $this->addFlash('song_error', 'Nie udało się dodać piosenki');
            return $this->redirectToRoute("index");
        }

        $this->songsList->load();
        $data = $form->getData();

        $song = new Song(
                $this->songsList->nextId(), $data['name'], $data['url'], 'off'
        );

        $this->songsList->addSong($song);
        $this->songsList->persist();

        $this->addFlash('song_success', 'Piosenka została dodana!');
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/songs/{id}/remove", name="remove_song")
     */
    public function removeSong($id)
    {
        $this->songsList->load();
        $song = $this->songsList->findById($id);

        if ($song->isPlaying()) {
            $this->addFlash("song_error", "Nie można usunąć z listy piosenki, która jest obecnie odtwarzana");
            return $this->redirectToRoute("index");
        }

        $this->songsList->remove($id);
        $this->songsList->persist();

        $this->addFlash("song_success", "Usunięto piosenkę {$song->name}");
        return $this->redirectToRoute("index");
    }

}
