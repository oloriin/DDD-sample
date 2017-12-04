<?php
namespace CoreDomain\Company;

use Doctrine\Common\Persistence\ObjectRepository;

interface CompanyRepositoryInterface extends ObjectRepository
{
    public function prepareBatchSave(Company $company);

    public function doBatchSave();

    public function save(Company $company);
}