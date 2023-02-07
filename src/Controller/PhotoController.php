<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Photo;
use App\Form\Photo\CommentType;
use App\Form\Photo\NewPhotoType;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

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
        $user = $this->getUser();
        $photos = $user->getPhotos();
        return $this->render('photo/manage.html.twig', ['photos' => $photos]);
    }

    #[Route('/photo/show/{id}', name: 'photo.show')]
    public function show(HttpFoundationRequest $request, Photo $photo, EntityManagerInterface $em): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            $comment = new Comment();
            $comment->setUser($user);
            $comment->setPhoto($photo);
            $comment->setCreatedAt(new \DateTimeImmutable());

            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($comment);
                $em->flush();
                return $this->redirectToRoute('photo.show', ['id' => $photo->getId()]);
            } else {
                return $this->render('photo/show.html.twig', ['photo' => $photo, 'form' => $form->createView()]);
            }
        } else {
            return $this->render('photo/show.html.twig', ['photo' => $photo]);
        }
    }



    #[Route('/photo/delete/{id}', name: 'photo.delete', condition: "context.getMethod() in ['DELETE', 'GET']")]
    public function delete(Photo $photo, EntityManagerInterface $manager): JsonResponse
    {
        // $photo = $photoRepository->find($request->get('id'));
        // //    $manager = $this->getDoctrine()->getManager();     
        // $manager->remove($photo);
        return new JsonResponse(['sucess' => 'Votre photo a été suprrimé', 'id' => $photo->getId()]);
        // $manager->flush();
        // return $this->redirectToRoute('photo.manage');
    }

    #[ROUTE('/photo/new', name: 'photo.new')]
    public function new(EntityManagerInterface $manager, HttpFoundationRequest $request)
    {

        $photo = new Photo();
        $form = $this->createForm(NewPhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo->setUser($this->getUser());
            $photo->setPostAt(new \DateTimeImmutable());
            $manager->persist($photo);
            $this->addFlash('succes', 'Votre photo a bien été ajouté');
            $manager->flush();
            return $this->redirectToRoute('photo.manage');
        }


        return $this->render('photo/new.html.twig', [
            'newForm' => $form->createView()
        ]);
    }
}
