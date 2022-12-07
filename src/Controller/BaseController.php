<?php

namespace App\Controller;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFields\EnumModel;
use AmoCRM\Models\CustomFieldsValues\DateCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\RadiobuttonCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\DateCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\RadiobuttonCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\DateCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\RadiobuttonCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\LinkModel;
use AmoCRM\Models\TaskModel;
use App\Form\CommonType;
use App\Service\AmoCrmApi\AmoCrmApiClientFacade;
use App\Service\AmoCrmApi\CustomFields\BirthdayCustomField;
use App\Service\AmoCrmApi\CustomFields\SexCustomField;
use DateTime;
use DateTimeZone;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/person', name: 'person', methods: 'GET|POST')]
    public function person(Request $Request, AmoCrmApiClientFacade $AmoApiFacade)
    {
        if (!$AmoApiFacade->hasValidToken()) {
            return $this->redirect($AmoApiFacade->getAuthUrl());
        }
        set_time_limit(0);

        $chunkSize = 1;
        $maxChunks = 1;

        $ContactService = $AmoApiFacade->contacts();
        for ($chunk = 1; $chunk <= $maxChunks; ++$chunk) {
            for ($chunkIter = 1; $chunkIter <= $chunkSize; ++$chunkIter) {
                    $CustomFieldCollection = new CustomFieldsValuesCollection();
                    $PhoneField = (new NumericCustomFieldValuesModel())->setFieldId(274451)->setValues(
                        (new NumericCustomFieldValueCollection())->add(
                            (new NumericCustomFieldValueModel())
                                ->setValue(strval(rand(1, 20)))
                        )
                    );
                    $CustomFieldCollection
                        ->add($PhoneField);
                    $ContactModel = new ContactModel();
                $unicId = $chunkIter * $chunk;
                    $ContactModel
                        ->setName("Contact_$unicId");

                    $ContactModel->setCustomFieldsValues($CustomFieldCollection);
                    $ContactModel = $ContactService->addOne($ContactModel);
                $LeadsService = $AmoApiFacade->leads();
                try {
                    $LeadsService->addOne(
                        (new LeadModel())
                            ->setPrice(rand(100, 2141143))
                            ->setName("Lead_$unicId")
                                ->setContacts(
                                    (new ContactsCollection())
                                        ->add($ContactModel)
                                )
                    );
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                    exit;
                }
            }
        }
        return $this->json('ok');
    }

    #[
        Route('/token_hook', name: '%env(REDIRECT_ROUTE)%')]
    public function tokenHook(Request $Request, AmoCrmApiClientFacade $AmoApiFacade): Response
    {
        $AmoApiFacade->saveTokenByCode($Request->get('code', ''));

        return $this->redirectToRoute('person');
    }
}
