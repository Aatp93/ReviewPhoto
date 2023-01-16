<?php

namespace App\Controller;

use App\Entity\Photo;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PhotoRepository;

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
        return $this->render('photo/list.html.twig', ['photos' => $photos]);
    }
}
