<?php
namespace ApplicationLayerApiBundle\Controller;

use DomainLayer\Contact\Contact;
use DomainLayer\Contact\Message\Message;
use DomainLayer\Contact\Message\MessageRepositoryInterface;
use DomainLayer\TreeGetSetNormalizer;
use DomainLayer\User\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

class MessageAPIController extends Controller
{
    /**
     * @ApiDoc(
     *  description="List contact message",
     *  headers={ { "name"="sample-ApiToken", "description"="Authorization token","required"="true" } },
     *  filters={
     *      {"name"="offset", "dataType"="integer"},
     *      {"name"="limit", "dataType"="integer"}
     *  },
     *  section = "Contact message"
     * )
     * @Route(  "/v1/contact/{contactId}/message",
     *          requirements={"contactId" = "\d+"},
     *          name="api_get_contact_message_list"
     * )
     * @Method("GET")
     * @param int $contactId
     * @param Request $request
     * @return JsonResponse
     */
    public function getMessageListAction(int $contactId, Request $request)
    {
        /**
         * @var MessageRepositoryInterface $contactRepository
         * @var User $user
         */
        $user = $this->getUser();
        $messageRepository = $this->get('infrastructure.repository.message');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $contact = $this->get('infrastructure.repository.contact')
            ->findOneBy(['company' => $user->getCompany(), 'id' => $contactId]);

        if (empty($contact)) {
            $error = ['message' => "Not found contact id: ".$contactId,];
            return new JsonResponse($error, 404, [], true);
        }

        $filter = ['contact' => $contact];
        if ($request->get('messageServiceType') !== null) {
            $filter['messageServiceType'] = $request->get('messageServiceType');
        }
        $messageList = $messageRepository->findBy($filter, ['createAt' => 'ASC'], $limit, $offset);

        $data = $this->serialize($messageList);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @ApiDoc(
     *  description="Add contact message",
     *  headers={ { "name"="sample-ApiToken", "description"="Authorization token","required"="true" } },
     *  section = "Contact message"
     * )
     * @Route(  "/v1/contact/{contactId}/message",
     *          requirements={"contactId" = "\d+"},
     *          name="api_post_contact_message"
     * )
     * @Method("POST")
     * @param int $contactId
     * @param Request $request
     * @return JsonResponse
     */
    public function postMessageAction(int $contactId, Request $request)
    {
        /**
         * @var MessageRepositoryInterface $messageRepository
         * @var User $user
         */
        $user = $this->getUser();
        $messageRepository = $this->get('infrastructure.repository.message');

        /** @var Contact $contact */
        $contact = $this->get('infrastructure.repository.contact')
            ->findOneBy(['company' => $user->getCompany(), 'id' => $contactId]);

        if (empty($contact)) {
            $error = ['message' => "Not found contact id: ".$contactId,];
            return new JsonResponse($error, 404, [], true);
        }

        $messageData = json_decode($request->getContent(), true);

        $createAt = new \DateTime();
        $createAt->setTimestamp($messageData['createAt']);

        $message = new Message(
            $contact,
            $messageData['body'],
            $createAt,
            $messageData['messageServiceType'],
            $messageData['senderAppType'],
            $messageData['directionType'],
            $messageData['contactRead'],
            $messageData['operatorRead']
        );

        $messageRepository->save($message);

        $data = $this->serialize($message);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @ApiDoc(
     *  description="Change contact message",
     *  headers={ { "name"="sample-ApiToken", "description"="Authorization token","required"="true" } },
     *  section = "Contact message"
     * )
     * @Route(  "/v1/contact/{contactId}/message/{messageId}",
     *          requirements={"contactId" = "\d+", "messageId" = "\d+"},
     *          name="api_put_contact_message"
     * )
     * @Method("PUT")
     * @param int $contactId
     * @param int $messageId
     * @param Request $request
     * @return JsonResponse
     */
    public function putMessageAction(int $contactId, int $messageId, Request $request)
    {
        /**
         * @var MessageRepositoryInterface $messageRepository
         * @var User $user
         * @var Message $message
         */
        $user = $this->getUser();
        $messageRepository = $this->get('infrastructure.repository.message');

        $contact = $this->get('infrastructure.repository.contact')
            ->findOneBy(['company' => $user->getCompany(), 'id' => $contactId]);
        if (empty($contact)) {
            $error = ['message' => "Not found contact id: ".$contactId,];
            return new JsonResponse($error, 404, [], true);
        }

        $message = $messageRepository->findOneBy(['contact' => $contact, 'id' => $messageId]);
        if (empty($message)) {
            $error = ['message' => "Not found message id: ".$messageId,];
            return new JsonResponse($error, 404, [], true);
        }

        $messageData = json_decode($request->getContent(), true);

        $message->setContactRead($messageData['contactRead']);
        $message->setOperatorRead($messageData['operatorRead']);
        $messageRepository->save($message);

        $data = $this->serialize($message);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param object $object
     * @return string
     */
    private function serialize($object): string
    {
        $context = Message::getStandardNormalizeContext();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new TreeGetSetNormalizer();

        $serializer = new Serializer([$normalizer], $encoders);

        return $serializer->serialize($object, 'json', $context);
    }
}
