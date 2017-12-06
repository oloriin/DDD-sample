<?php
namespace ApplicationLayerApiBundle\Controller;

use DomainLayer\TreeGetSetNormalizer;
use DomainLayer\User\User;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

class UserAPIController extends Controller
{
    /**
     * @ApiDoc(
     *  description="User list",
     *  headers={ { "name"="sample-ApiToken", "description"="Authorization token","required"="true" } },
     *  section = "User"
     * )
     * @Route("/v1/user", name="api_user_list")
     * @Method("GET")
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserListAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $userRepository = $this->get('infrastructure.repository.user');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $userList = $userRepository->findBy(['company' => $user->getCompany()], null, $limit, $offset);

        return new JsonResponse($this->serialize($userList), 200, [], true);
    }

    /**
     * @ApiDoc(
     *  description="Create User",
     *  headers={ { "name"="sample-ApiToken", "description"="Authorization token","required"="true" } },
     *  section = "User"
     * )
     * @Route("/v1/user", name="user_registration")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function registerAction(Request $request)
    {
        /** @var User $user */
        $currentUser = $this->getUser();

        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** Validation */

        $userData = json_decode($request->getContent(), true);

        $user->setCompany($currentUser->getCompany());
        $user->setUsername($userData['email']);
        $user->setEmail($userData['email']);
        $user->setPhone($userData['phone']);
        $user->setPlainPassword($userData['password']);
        $user->generateApiToken();

        $userManager->updateUser($user);

        if (isset($userData['avatar'])) {
            $avatarInBase64 = $userData['avatar'];
            $jpegMeta = 'data:image/jpeg;base64,';
            $pngMeta = 'data:image/png;base64,';

            if (strpos($avatarInBase64, $jpegMeta) === 0) {
                $avatarImgType = 'jpeg';
                $avatarData = str_replace($jpegMeta, '', $avatarInBase64);
            } elseif (strpos($avatarInBase64, $pngMeta) === 0) {
                $avatarImgType = 'png';
                $avatarData = str_replace($pngMeta, '', $avatarInBase64);
            } else {
                /**
                 * @TODO Exception
                 */
                return null;
            }

            $avatarData = base64_decode($avatarData);
            $avatarTmpPatch = '/tmp/avatar_'.time().'.jpeg';
            file_put_contents($avatarTmpPatch, $avatarData);
            $user->setAvatar($avatarTmpPatch, $avatarImgType);
        }

        $userManager->updateUser($user);

        return new JsonResponse(json_encode([]), 201, [], true);
    }

    /**
     * @ApiDoc(
     *  description="Create User",
     *  section = "User",
     *   filters={
     *      {"name"="email", "dataType"="string"},
     *      {"name"="password", "dataType"="string"}
     *  }
     * )
     * @Route("/v1/user/generateAPIToken", name="api_user_generate_apitoken")
     * @Method("GET")
     * @return JsonResponse
     */
    public function getGenerateApiTokenAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $user->generateApiToken();
        $tokenData = ['apiToken' => $user->getApiToken()];

        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        $userManager->updateUser($user);

        return new JsonResponse(json_encode($tokenData), 200, [], true);
    }


    /**
     * @ApiDoc(
     *  description="User self",
     *  headers={ { "name"="sample-ApiToken", "description"="Authorization token","required"="true" } },
     *  section = "User"
     * )
     * @Route("/v1/user/self", name="user_self")
     * @Method("GET")
     * @return JsonResponse
     */
    public function getSelfUserAction()
    {
        return new JsonResponse($this->serialize($this->getUser()), 200, [], true);
    }

    /**
     * @param object $object
     * @return string
     */
    private function serialize($object): string
    {
        $context = User::getStandardNormalizeContext();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new TreeGetSetNormalizer();

        $serializer = new Serializer([$normalizer], $encoders);

        return $serializer->serialize($object, 'json', $context);
    }
}
