<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerController extends AbstractController
{
    #[Route('/customer/submit')]
    public function submitEdit(Request $request): JsonResponse
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->json(['saved' => true]);
        }

        $errors = [];
        /** @var FormError $error */
        foreach ($form->getErrors(true) as $error) {
            $view = $error->getOrigin()->createView();
            $errors[$view->vars['id']][] = $error->getMessage();
        }

        return $this->json(['saved' => false, 'errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
