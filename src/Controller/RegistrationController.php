<?php

namespace App\Controller;

use App\Entity\ActorEconomico;
use App\Entity\Empresa;
use App\Entity\Invitaciones;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserRepresentanteType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\s;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
          /*  $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('oberroaa@gmail.com', 'Proyecto'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );*/
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
    private function get_user_auto_gen($text){
        $usern = mb_split('@',$text);
        $userx = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$usern[0]]);
        if (is_null($userx))
            return $usern[0];
        else{
            $n = rand(1000,9999);
            $nx = $usern[0].$n;
            $userx = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$nx]);
            if (is_null($userx))
                return $usern[0].$n;
            else
                return '';
        }

    }
    /**
     * @Route("/register/admin/empresa/{link}", name="app_register_admin_empresa", methods={"GET", "POST"})
     */
    public function register_admin_empresa(Request $request, UserPasswordHasherInterface $userPasswordHasher,
                                           EntityManagerInterface $entityManager,$link): Response
    {

        $cod = base64_decode($link);
        $codx = mb_split('-',$cod);
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->find($codx[0]);
        if (is_null($empresa))
            throw new \Exception('El ID de socio no existe.');
        $actorE = $this->getDoctrine()->getRepository(ActorEconomico::class)->findOneBy(['codigo'=>$codx[1]]);
        if (is_null($actorE))
            throw new \Exception('El ID de socio no existe.');



        $user = new User();
        $user->setUsername( $this->get_user_auto_gen($empresa->getRepresentantoEmail()));
        $user->setEmail($empresa->getRepresentantoEmail());
        $user->setNombreApellidos($empresa->getRepresentante());
        $form = $this->createForm(UserRepresentanteType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmpresa($empresa);
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('home');
        }

        return $this->render('registration/new.html.twig', [
            'form' => $form->createView(),
            'user'=>$user,
            'actor'=>$actorE
        ]);
    }

    /**
     * @Route("/register/user/empresa/{link}", name="app_register_user_empresa", methods={"GET", "POST"})
     */
    public function register_user_empresa(Request $request, UserPasswordHasherInterface $userPasswordHasher,
                                           EntityManagerInterface $entityManager,$link): Response
    {

        $cod = base64_decode($link);
        //var_dump($cod);
        $codx = mb_split('@',$cod);
        //var_dump($cod,$codx);
        $invitacion = $this->getDoctrine()->getRepository(Invitaciones::class)->find($codx[0]);
        if (is_null($invitacion))
            throw new \Exception('La invitación no existe.');
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->find($codx[1]);
        if (is_null($empresa))
            throw new \Exception('La empresa no existe.');

        $user = new User();
        $user->setUsername( $this->get_user_auto_gen($invitacion->getCorreo()));
        $user->setEmail($invitacion->getCorreo());
        $user->setNombreApellidos($invitacion->getNombre());
        $form = $this->createForm(UserRepresentanteType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmpresa($empresa);
            $user->setLinkAcceso($empresa->getLink());
            $invitacion->setEstado('aceptada');
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('home');
        }

        return $this->render('registration/new.html.twig', [
            'form' => $form->createView(),
            'user'=>$user,
            'actor'=>$empresa->getActoreconomico()
        ]);
    }

}
