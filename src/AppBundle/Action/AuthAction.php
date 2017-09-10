<?php
/**
 * This file is part of the 'arena' project'.
 *
 * (c) Víctor Monserrat Villatoro <victor1995mv@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Action;

use AppBundle\Factory\UserFactory;
use AppBundle\Repository\UserRepository;
use AppBundle\Services\UserManager;
use GuzzleHttp\Client;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthAction.
 */
class AuthAction
{
    const GOOGLE_API_ME = 'https://www.googleapis.com/plus/v1/people/me';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var JWTManager
     */
    private $JWTManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AuthAction constructor.
     *
     * @param UserRepository  $userRepository
     * @param UserFactory     $userFactory
     * @param UserManager     $userManager
     * @param JWTManager      $JWTManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserRepository $userRepository,
        UserFactory $userFactory,
        UserManager $userManager,
        JWTManager $JWTManager,
        LoggerInterface $logger
        ) {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->userManager = $userManager;
        $this->JWTManager = $JWTManager;
        $this->logger = $logger;
    }

    /**
     * @Route("/auth", name="new_jwt_token")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $content = $request->getContent();
        if (empty($content)) {
            return new JsonResponse(['error' => 'Response empty'], Response::HTTP_FORBIDDEN);
        } else {
            $params = \GuzzleHttp\json_decode($content, true);
        }

        $network = $params['network'];
        $socialToken = $params['socialToken'];

        if ('google' !== $network) {
            return new JsonResponse(['error' => 'Network not supported'], Response::HTTP_FORBIDDEN);
        }

        try {
            $client = new Client();
            $response = $client->request('GET', static::GOOGLE_API_ME, [
                                'headers' => [
                                            'Authorization' => 'Bearer '.$socialToken,
                                        ],
                                ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }

        $content = $response->getBody()->getContents();
        $metadata = \GuzzleHttp\json_decode($content, true);

        $email = $metadata['emails'][0]['value'];
        $avatar = $metadata['image']['url'];
        $fullName = $metadata['displayName'];

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            $user = $this->userFactory->create($email, $email, $email);
            $user->setAvatar($avatar);
            $user->setFullName($fullName);
            $this->userManager->create($user);
        } else {
            $user->setAvatar($avatar);
            $this->userManager->update($user);
        }

        $token = $this->JWTManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
