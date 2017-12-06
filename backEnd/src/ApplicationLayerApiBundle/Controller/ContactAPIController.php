<?php
namespace ApplicationLayerApiBundle\Controller;

use DomainLayer\Contact\Contact;
use DomainLayer\Contact\ContactRepositoryInterface;
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

class ContactAPIController extends Controller
{
    /**
     * @ApiDoc(
     *  description="List contact",
     *  headers={ { "name"="sample-ApiToken", "description"="Authorization token","required"="true" } },
     *  section = "Contact",
     *  filters={
     *      {"name"="offset", "dataType"="integer"},
     *      {"name"="limit", "dataType"="integer"},
     *      {"name"="presence", "dataType"="string", "variant"="ONLINE, IDLE, OFFLINE"}
     *  }
     * )
     * @Route("/v1/contact", name="api_get_contact")
     * @Method("GET")
     * @param Request $request
     * @return JsonResponse
     */
    public function getContactList(Request $request)
    {
        /**
         * @var ContactRepositoryInterface $contactRepository
         * @var User $user
         */
        $user = $this->getUser();
        $contactRepository = $this->get('infrastructure.repository.contact');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);

        $filter = ['company' => $user->getCompany()];
        if ($request->get('presence') !== null) {
            $filter['presence'] = $request->get('presence');
        }

        $contactList = $contactRepository
            ->findBy($filter, null, $limit, $offset);

        $data = $this->serialize($contactList);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param mixed $object
     * @return string
     */
    private function serialize($object): string
    {
        $context = Contact::getStandardNormalizeContext();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new TreeGetSetNormalizer();

        $serializer = new Serializer([$normalizer], $encoders);

        return $serializer->serialize($object, 'json', $context);
    }
}
