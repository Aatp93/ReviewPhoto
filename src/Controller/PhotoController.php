<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PhotoController extends AbstractController
{
    #[Route('/', name: 'photo.list')]
    public function list(PhotoRepository $photoRepository): Response
    {
        // $photo = new Photo();
        // $photo->setTitle('Ma première photo');
        // $photo->setPostAt(new DateTimeImmutable());
        // $photos = [];
        // $photos[] = $photo;
        $photos = $photoRepository->findAll();
        dump($photos);
        return $this->render('photo/list.html.twig', ['photos' => $photos]);
    }

    #[Route('/photo/manage', name: 'photo.manage')]
    public function manage()
    {
        $user = $this ->getUser(); 
        $photos = $user ->getPhotos();
        return $this-> render('photo/manage.html.twig', ['photos'=>$photos]);
    }

    #[Route('/photo/show/{id}', name: 'photo.show')]
    public function show(PhotoRepository $photoRepository, HttpFoundationRequest $request): Response
    {

        $photo = $photoRepository->find($request->get('id'));
        return $this->render('photo/show.html.twig', ['photo' => $photo]);
    }

   

    #[Route('/photo/delete/{id}', name: 'photo.delete')]
    public function delete(PhotoRepository $photoRepository, HttpFoundationRequest $request, EntityManagerInterface $manager): Response
    {
       $photo = $photoRepository->find($request->get('id')); 
    //    $manager = $this->getDoctrine()->getManager();     
       $manager ->remove($photo);
       $manager->flush();
       return $this->redirectToRoute('photo.manage');
    }

    #[ROUTE('/photo/add', name:'photo.add')]
    public function add(){
                    
        return $this->render('photo/add.html.twig');

    }

}
