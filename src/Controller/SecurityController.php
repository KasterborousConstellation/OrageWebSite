<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
class SecurityController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[IsGranted('ROLE_USER')]
    #[Route(path: '/verify_email_resend', name: 'app_verify_email_resend')]
    public function resendVerificationEmail(VerifyEmailHelperInterface $emailHelper): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
        if ($user->isVerified()) {
            $this->addFlash('error', 'Votre email est déja vérifié.');

            return $this->redirectToRoute('app_profile');
        }
        $this->addFlash('success', 'Un nouveau mail de vérification a été envoyé à votre adresse mail.');
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('contact@asso-orage.fr', 'Association Orage'))
                ->to((string) $user->getEmail())
                ->subject('Confirmez votre mail.')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        return $this->redirectToRoute('app_profile');
    }
}
