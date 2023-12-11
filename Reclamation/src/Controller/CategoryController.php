<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CreateCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/new', name: 'app_category_index', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Category::class)
            ->findAll();

        $category = new Category();
        $form = $this->createForm(CreateCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // if modele or marque are empty

            if (empty($category->getMarque()) || empty($category->getModele())) {
                $this->addFlash('error', 'Le champ marque et modèle sont obligatoires');
                return $this->redirectToRoute('app_category_index');
            }

            $entityManager->persist($category);
            $entityManager->flush();

            // if successful
            $this->addFlash('success', 'La catégorie a bien été créée');

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/category.html.twig', [
            'category' => $category,
            'form' => $form,
            'categories' => $categories,
        ]);
    }


    #[Route('/edit/{id}', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreateCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/edit-category.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
