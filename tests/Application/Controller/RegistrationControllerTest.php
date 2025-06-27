<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegisterControllerReturnsTheCorrectBody(): void
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');
    }

    public function testSuccessfulRegistration(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $email = 'test@'.uniqid().'.test';
        $password = '123456';

        $client->submitForm('Register', [
            'registration_form[email]' => $email,
            'registration_form[plainPassword][first]' => $password,
            'registration_form[plainPassword][second]' => $password,
            'registration_form[agreeTerms]' => true,
        ]);

        $this->assertResponseRedirects();
    }

    public function testVerifyEmailSuccess(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);
        $verifyEmailHelper = $container->get(VerifyEmailHelperInterface::class);

        // Create and persist a test user
        $user = new User();
        $user->setEmail('verifytest@example.example');
        $user->setPassword('irrelevant');
        $user->setIsVerified(false);
        $entityManager->persist($user);
        $entityManager->flush();

        // Log in user so 'IS_AUTHENTICATED_FULLY' passes
        $client->loginUser($user);

        // Generate signed URL with verifyEmailHelper
        $signatureComponents = $verifyEmailHelper->generateSignature(
            'app_verify_email',
            $user->getUserIdentifier(),
            $user->getEmail(),
            ['uuid' => $user->getUuid()]
        );

        // Call verify email URL - this triggers handleEmailConfirmation
        $client->request('GET', $signatureComponents->getSignedUrl());

        $this->assertResponseRedirects('/register');

        // Refresh user from DB and assert email is verified
        $entityManager->refresh($user);
        $this->assertTrue($user->isVerified());
    }

}
