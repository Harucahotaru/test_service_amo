<?php

namespace App\Service\AmoCrmApi;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\EntitiesServices\Account;
use AmoCRM\EntitiesServices\Calls;
use AmoCRM\EntitiesServices\CatalogElements;
use AmoCRM\EntitiesServices\Catalogs;
use AmoCRM\EntitiesServices\Chats\Templates;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\EntitiesServices\Currencies;
use AmoCRM\EntitiesServices\Customers\Customers;
use AmoCRM\EntitiesServices\CustomFieldGroups;
use AmoCRM\EntitiesServices\CustomFields;
use AmoCRM\EntitiesServices\Events;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\EntitiesServices\Leads\LossReasons;
use AmoCRM\EntitiesServices\Leads\Pipelines;
use AmoCRM\EntitiesServices\Products;
use AmoCRM\EntitiesServices\Roles;
use AmoCRM\EntitiesServices\Segments;
use AmoCRM\EntitiesServices\ShortLinks;
use AmoCRM\EntitiesServices\Sources;
use AmoCRM\EntitiesServices\Talks;
use AmoCRM\EntitiesServices\Tasks;
use AmoCRM\EntitiesServices\Unsorted;
use AmoCRM\EntitiesServices\Users;
use AmoCRM\EntitiesServices\Webhooks;
use AmoCRM\EntitiesServices\Widgets;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\CustomFields\CustomFieldModel;
use App\Service\AmoCrmApi\CustomFields\CustomFieldsFactory;
use Exception;

/**
 * @method Leads             leads()
 * @method Contacts          contacts()
 * @method Account           account()
 * @method Roles             roles()
 * @method Users             users()
 * @method Segments          customersSegments()
 * @method Events            events()
 * @method Webhooks          webhooks()
 * @method Unsorted          unsorted()
 * @method Pipelines         pipelines()
 * @method Sources           sources()
 * @method Templates         chatTemplates()
 * @method Widgets           widgets()
 * @method ShortLinks        shortLinks()
 * @method LossReasons       lossReasons()
 * @method Customers         customers()
 * @method Calls             calls()
 * @method Products          products()
 * @method Talks             talks()
 * @method Tasks             tasks()
 * @method Currencies        currencies()
 * @method Catalogs          catalogs()
 * @method CatalogElements   catalogElements(int $catalogId = null)
 * @method CustomFieldGroups customFieldGroups(string $entityType = null)
 * @method CustomFields      customFields(string $entityType)
 */
class AmoCrmApiClientFacade
{
    private AmoCRMApiClient $Client;

    public function __construct(
        private readonly AmoCRMApiClientFactoryAdapter $ClientFactory,
        private readonly CustomFieldsFactory $CustomFieldsFactory
    ) {
    }

    /**
     * @return AmoCRMApiClient
     */
    protected function getClient(): AmoCRMApiClient
    {
        if (!isset($this->Client)) {
            $this->Client = $this->ClientFactory->make();
        }

        return $this->Client;
    }

    /**
     * @throws AmoCRMoAuthApiException
     */
    public function saveTokenByCode(string $code): void
    {
        $Client = $this->getClient();
        $Config = $this->ClientFactory->getConfig();
        $baseDomain = $Config->getBaseDomain();

        $Client->setAccountBaseDomain($baseDomain);
        $Token = $Client->getOAuthClient()->getAccessTokenByCode($code);
        $this->ClientFactory->getService()->saveOAuthToken($Token, $baseDomain);
    }

    /**
     * @throws Exception
     */
    public function getAuthUrl(): string
    {
        return $this->getClient()->getOAuthClient()->getAuthorizeUrl(
            [
                'state' => $this->getTmpState(),
                'mode' => 'post_message',
            ]
        );
    }

    /**
     * @return bool
     */
    public function hasValidToken(): bool
    {
        return null !== $this->ClientFactory->getService()->getOAuthToken();
    }

    /**
     * @throws Exception
     *
     * @todo    neadekvatno
     */
    protected function getTmpState(): string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        $Client = $this->getClient();
        if (!$Client->isAccessTokenSet()) {
            $Token = $this->ClientFactory->getService()->getOAuthToken();
            $baseDomain = $this->ClientFactory->getConfig()->getBaseDomain();
            if ($Token) {
                $Client->setAccessToken($Token)->setAccountBaseDomain($baseDomain);
            }
        }

        return call_user_func_array([$this->getClient(), $name], $arguments);
    }

    /**
     * @throws Exception
     */
    public function getOrCreateCustomField(string $entityType, string $fieldCode): CustomFieldModel
    {
        $FieldsService = $this->customFields($entityType);
        $FieldModel = $this->filterFieldModel($FieldsService->get(), $fieldCode);
        if (!$FieldModel) {
            $CustomField = $this->CustomFieldsFactory->getField($fieldCode);
            $FieldModel = $FieldsService->addOne($CustomField->create());
        }

        return $FieldModel;
    }

    /**
     * @param CustomFieldsCollection $FieldsCollection
     * @param string $code
     * @return ?CustomFieldModel
     */
    protected function filterFieldModel(CustomFieldsCollection $FieldsCollection, string $code): ?CustomFieldModel
    {
        /** @var CustomFieldModel $Model */
        foreach ($FieldsCollection as $Model) {
            if ($Model->getCode() === $code) {
                return $Model;
            }
        }

        return null;
    }
}
