<?php

namespace App\Controller;

use App\Entity\QuestionCategory;
use App\Form\QuestionCategoryType;
use App\Repository\QuestionCategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/question/categories')]
class QuestionCategoriesController extends AbstractController
{
    #[Route('/', name: 'app_question_categories_index', methods: ['GET'])]
    public function index(QuestionCategoryRepository $questionCategoryRepository): Response
    {
        return $this->render('question_categories/index.html.twig', [
            'question_categories' => $questionCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_question_categories_new', methods: ['GET', 'POST']), IsGranted("ROLE_ADMIN")]
    public function new(Request $request, QuestionCategoryRepository $questionCategoryRepository): Response
    {
        $questionCategory = new QuestionCategory();
        $form = $this->createForm(QuestionCategoryType::class, $questionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionCategoryRepository->save($questionCategory, true);

            return $this->redirectToRoute('app_question_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question_categories/new.html.twig', [
            'question_category' => $questionCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_question_categories_show', methods: ['GET']), IsGranted("ROLE_ADMIN")]
    public function show(QuestionCategory $questionCategory): Response
    {
        return $this->render('question_categories/show.html.twig', [
            'question_category' => $questionCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_question_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuestionCategory $questionCategory, QuestionCategoryRepository $questionCategoryRepository): Response
    {
        $form = $this->createForm(QuestionCategoryType::class, $questionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionCategoryRepository->save($questionCategory, true);

            return $this->redirectToRoute('app_question_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question_categories/edit.html.twig', [
            'question_category' => $questionCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_question_categories_delete', methods: ['POST']), IsGranted("ROLE_ADMIN")]
    public function delete(Request $request, QuestionCategory $questionCategory, QuestionCategoryRepository $questionCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $questionCategory->getId(), $request->request->get('_token'))) {
            $questionCategoryRepository->remove($questionCategory, true);
        }

        return $this->redirectToRoute('app_question_categories_index', [], Response::HTTP_SEE_OTHER);
    }
}
