<?php
namespace ApplicationLayerApiBundle\Controller;

use DomainLayer\Company\Company;
use DomainLayer\User\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class CompanyAPIController extends Controller
{
    /**
     * @ApiDoc(
     *  description="Concrete company",
     *  headers={ { "name"="sample-ApiToken", "description"="Authorization token","required"="true" } },
     *  section = "Company"
     * )
     * @Route("/v1/company/{id}", requirements={"id" = "\d+"}, name="api_get_company")
     * @Method("GET")
     * @param int $id
     * @return JsonResponse
     */
    public function getCompanyAction(int $id)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($id != $user->getCompany()->getId()) {
            $error = ['message' => "You don't access company id: ".$id,];
            return new JsonResponse($error, 403, [], true);
        }

        $company = $this->get('doctrine.orm.default_entity_manager')->getRepository(Company::class)->find($id);

        $data = $this->getSerializer()->serialize($company, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @ApiDoc(
     *  description="Change company",
     *  headers={ { "name"="sample-ApiToken", "description"="Authorization token","required"="true" } },
     *  section = "Company"
     * )
     * @Route("/v1/company/{id}", requirements={"id" = "\d+"}, name="api_put_company")
     * @Method("PUT")
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function putCompanyAction(int $id, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($id != $user->getCompany()->getId()) {
            $error = ['message' => "You don't access company id: ".$id,];
            return new JsonResponse($error, 403, [], true);
        }

        $companyRepository = $this->get('doctrine.orm.default_entity_manager')->getRepository(Company::class);
        $company = $companyRepository->find($id);

        $companyData = json_decode($request->getContent());
        $company->setName($companyData->name);
        $companyRepository->save($company);

        $data = $this->getSerializer()->serialize($company, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    /** @return Serializer */
    private function getSerializer(): Serializer
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes(['contact', 'segment', 'crmServices', 'messageService']);

        return new Serializer([$normalizer], $encoders);
    }
}
