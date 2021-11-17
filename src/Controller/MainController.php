<?php

namespace App\Controller;

use App\Entity\De;
use App\Entity\Player;
use App\Form\PlayerType;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{

    /**
     * @Route("/hub", name="hub")
     */
    public function hub(EntityManagerInterface $em, Request $request): Response
    {
    
        $player1 = new Player();
        $player1form = $this->createForm(PlayerType::class, $player1);
        $player1form->handleRequest($request);

        if ($player1form->isSubmitted() && $player1form->isValid()) { 

            $player1->setNbcases(0);
            $player1->setState(0);

            $em->persist($player1);
            $em->flush();

            return $this->redirectToRoute('hubSuite');
        }

        return $this->render('main/hub.html.twig', [
            'player1form' => $player1form->createView(),
        ]);
    }

    /**
     * @Route("/hubSuite", name="hubSuite")
     */
    public function hubSuite(EntityManagerInterface $em, Request $request): Response
    {
        $player2 = new Player();
        $player2form = $this->createForm(PlayerType::class, $player2);
        $player2form->handleRequest($request);

        if ($player2form->isSubmitted() && $player2form->isValid()) { 

            $player2->setNbcases(0);
            $player2->setState(1);

            $em->persist($player2);
            $em->flush();

            return $this->redirectToRoute('main');
        }  
        return $this->render('main/hub1.html.twig', [
            'player2form' => $player2form->createView()
        ]);
    }
    /**
     * @Route("/main", name="main")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $allPlayers = $this->getDoctrine()
            ->getRepository(Player::class)
            ->findAll();
        
        $dé = $this->getDoctrine()
        ->getRepository(De::class)
        ->find(1);

        $lastTwoPlayers = array_slice($allPlayers, -2);

        $messageWin ='';

        $messageError ='';

        foreach($lastTwoPlayers as $player){
            if($player->getState() == 0)
            $currentPlayer = $player;

            if($player->getNbCases() == 50){
                $messageWin = 'Le joueur '.$player->getName().' a gagné !';
            }

            if($player->getNbCases() > 50){
                $messageWin = 'Le joueur '.$player->getName().' est descendu de 25 cases ! :(';
                $player->setNbCases(25);
                $em->persist($player);
                $em->flush();
            }
        }
        
        return $this->render('main/index.html.twig', [
            'dé' => $dé->getNumber(),
            'currentPlayer' => $currentPlayer,
            'messageWin' => $messageWin,
            'messageError' => $messageError,
            'lastTwoPlayers' => $lastTwoPlayers
        ]);
    }

    /**
     * @Route("/lancerde", name="lancerde")
     */
    public function lancerDé(EntityManagerInterface $em, Request $request): Response
    {
        $dé = $this->getDoctrine()
        ->getRepository(De::class)
        ->find(1);

        $dé->setNumber(rand(1, 6));
        $em->persist($dé);
        $em->flush();

        $allPlayers = $this->getDoctrine()
        ->getRepository(Player::class)
        ->findAll();
        
        $lastTwoPlayers = array_slice($allPlayers, -2);

        foreach($lastTwoPlayers as $player){
            if($player->getState() == 0){
            $player->setState(1);
            $player->setNbCases($player->getNbCases() + $dé->getNumber());
            $em->persist($player);
            $em->flush();
            }
            elseif($player->getState() == 1){
            $player->setState(0);
            $em->persist($player);
            $em->flush();
            }
        }
    
        return $this->redirect($request->headers->get('referer'));
    }


}
