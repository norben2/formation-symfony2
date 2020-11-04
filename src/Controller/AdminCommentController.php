<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Service\PaginationService;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {   
        $pagination->setEntity(Comment::class)
                   ->setLimit(5)
                   ->setCurrentPage($page);
        // $repo = $this->getDoctrine()->getRepository(Comment::class);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * permet d'éditer un commentaire 
     * @Route("/admin/comments/{id}/edit", name = "admin_comment_edit")
     *
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminCommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "la modification du commentaire de <strong>{$comment->getAuthor()->getfullName()}</strong> a bien été modifié"
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de supprimer un commentaire
     * @Route("/admin/comment/{id}/delete", name = "admin_comment_delete" )
     * @return void
     */
    public function delete(Comment $comment, ObjectManager $manager){
        if($comment){
            $manager->remove($comment);
            $manager->flush();
            $this->addFlash(
                'success',
                "le commentaire de {$comment->getAuthor()->getFullName()}à bien été supprimé"
            );
        }
       return $this->redirectToRoute('admin_comments_index');
    }
}
