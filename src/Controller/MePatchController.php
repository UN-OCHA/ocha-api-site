<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MePatchController extends AbstractController {

    public function __construct(private HttpKernelInterface $kernel)
    {
    }

    public function __invoke(Request $request, User $data, UserRepository $repository, TokenStorageInterface $tokenStorage): User
    {
        $user = $this->getCurrentUser($tokenStorage);

        if (!empty($data->getEmail())) {
          $user->setEmail($data->getEmail());
        }

        if (!empty($data->getWebhook())) {
          $user->setWebhook($data->getWebhook());
        }

        $repository->save($user, TRUE);

        return $user;
    }

    protected function getCurrentUser(TokenStorageInterface $tokenStorage) : User {
        /** @var \App\Entity\User */
        if ($tokenStorage->getToken() && $user = $tokenStorage->getToken()->getUser()) {
          return $user;
        }

        throw new AccessDeniedHttpException('This API Key does not have access to this endpoint.');
    }

}
