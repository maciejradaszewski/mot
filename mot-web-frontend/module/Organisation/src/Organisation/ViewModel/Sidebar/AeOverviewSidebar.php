<?php
namespace Organisation\ViewModel\Sidebar;

use Core\ViewModel\Sidebar\GeneralSidebar;
use Core\ViewModel\Sidebar\GeneralSidebarLink;
use Core\ViewModel\Sidebar\GeneralSidebarLinkList;
use Core\ViewModel\Sidebar\GeneralSidebarStatusBox;
use Core\ViewModel\Sidebar\GeneralSidebarStatusItem;
use Core\ViewModel\Sidebar\SidebarBadge;
use Core\ViewModel\Sidebar\SidebarButton;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode;
use Event\Controller\EventController;
use Organisation\Authorisation\AuthorisedExaminerViewAuthorisation;
use Organisation\Controller\MotTestLogController;
use SlotFinance\Controller\AdjustmentController;
use SlotFinance\Controller\Amendment\RefundController;
use SlotFinance\Controller\Purchase\IndexController as FinancePurchaseController;
use SlotPurchase\Controller\DirectDebit\AbstractDirectDebitController;
use SlotPurchase\Controller\Journey\AbstractJourneyController;
use SlotPurchase\Controller\Report\SlotUsageController;
use SlotPurchase\Controller\Report\TransactionHistoryController;
use SlotPurchase\Service\DirectDebitService;
use Zend\Mvc\Controller\Plugin\Url;

class AeOverviewSidebar extends GeneralSidebar{

    /** @var GeneralSidebarLinkList[] */
    private $linkLists = [];
    /**
     * @var AuthorisedExaminerViewAuthorisation
     */
    private $authorisationForView;

    private $url;
    /**
     * @var
     */
    private $organisationId;

    /** @var OrganisationDto */
    private $organisation;

    /**
     * @param $organisation
     * @param AuthorisedExaminerViewAuthorisation $authorisationForView
     * @param Url $url
     * @param DirectDebitService $directDebitService
     * @internal param $organisationId
     */
    public function __construct(
        OrganisationDto $organisation,
        AuthorisedExaminerViewAuthorisation $authorisationForView,
        Url $url,
        DirectDebitService $directDebitService
    )
    {
        $this->directDebitService = $directDebitService;
        $this->url = $url;
        $this->authorisationForView = $authorisationForView;
        $this->organisation = $organisation;
        $this->organisationId = $organisation->getId();

        $this->resolveLinks();
    }

    private function buildStatusBox()
    {
        $statusBox = new GeneralSidebarStatusBox();

        if($this->authorisationForView->canViewAeStatus()) {
            $aeAuth = $this->organisation->getAuthorisedExaminerAuthorisation();
            $aeAuthStatus = $aeAuth->getStatus();
            if (AuthorisationForAuthorisedExaminerStatusCode::WITHDRAWN === $aeAuthStatus->getCode()
                && !empty($aeAuth->getExpiryDate())) {
                $withdrawalDate = DateTimeDisplayFormat::textDate($aeAuth->getExpiryDate());
            } else {
                $withdrawalDate = "N/A";
            }

            $statusBox->addItem(new GeneralSidebarStatusItem("ae-status", "Status", $aeAuthStatus->getName(),
                SidebarBadge::success(), $withdrawalDate ));
        }

        if($this->authorisationForView->canViewSlotBalance()) {
            $statusBox->addItem(new GeneralSidebarStatusItem("slot-count", "Slots",
                number_format($this->organisation->getSlotBalance(), 0, '.', ','), SidebarBadge::info()));
        }

        $this->addItem($statusBox);
    }

    private function resolveLinks()
    {
        $this->buildStatusBox();
        $this->buildBuySlotsSection();
        $this->buildSlotsLinks();
        $this->buildRelatedLinks();
    }

    public function addSlotsLink($htmlId, $title, $url)
    {
        $this->addLinkToList("Slots", $htmlId, $title, $url);
        return $this;
    }

    public function addRelatedLink($htmlId, $title, $url)
    {
        $this->addLinkToList("Related", $htmlId, $title, $url);
        return $this;
    }

    private function addLinkToList($list, $htmlId, $title, $url)
    {
        if(!array_key_exists($list, $this->linkLists)) {
            $this->linkLists[$list] = new GeneralSidebarLinkList($list);
            $this->addItem($this->linkLists[$list]);
        }

        $this->linkLists[$list]->addLink(new GeneralSidebarLink($htmlId, $title, $url));
    }

    private function buildBuySlotsSection()
    {
        $list = new GeneralSidebarLinkList(null);

        if($this->authorisationForView->canSettlePayment()) {
            $url = $this->url->fromRoute(FinancePurchaseController::ROUTE_NAME_PURCHASE_TYPE,
                ['organisationId' => $this->organisationId]);
        } elseif($this->authorisationForView->canBuySlots()) {
            $url = $this->url->fromRoute(AbstractJourneyController::ROUTE_NAME_START,
                ['organisationId' => $this->organisationId]);
        }

        if(!empty($url)) {
            $list->addLink(new SidebarButton("add-slots", "Buy slots", $url));
        }

        $directDebitLink = $this->buildDirectDebitLink();

        if($directDebitLink) {
            $list->addLink($directDebitLink);
        }

        if(!empty($list->getLinks())) {
            $this->addItem($list);
        }
    }

    private function buildDirectDebitLink()
    {
        if(!$this->directDebitService->isMandateSetupInProgress($this->organisationId)) {
            if($this->directDebitService->isMandateSetup($this->organisationId)) {
                if($this->authorisationForView->canManageDirectDebit()) {
                    $link = new GeneralSidebarLink('manageDirectDebit', 'Manage Direct Debit',
                        $this->url->fromRoute(AbstractDirectDebitController::ROUTE_NAME_MANAGE, [
                            'organisationId' => $this->organisationId
                        ]));
                }
            } elseif($this->authorisationForView->canSetupDirectDebit()) {
                $link = new GeneralSidebarLink('setupDirectDebit', 'Setup Direct Debit',
                    $this->url->fromRoute(AbstractDirectDebitController::ROUTE_NAME_START, [
                        'organisationId' => $this->organisationId
                    ]));
            }

            if(!empty($link)) {
                return $link;
            }
        }
        return null;
    }

    private function buildSlotsLinks()
    {
        if($this->authorisationForView->canViewTransactionHistory()) {
            $this->addSlotsLink("transaction-history",
                "Transaction history",
                $this->url->fromRoute(TransactionHistoryController::ROUTE_NAME,
                    ['organisationId' => $this->organisationId])
            );
        }

        if($this->authorisationForView->canViewSlotUsage()) {
            $this->addSlotsLink("slot-usage",
                "Slot usage",
                $this->url->fromRoute(SlotUsageController::ROUTE_NAME,
                    ['organisationId' => $this->organisationId])
            );
        }

        if($this->authorisationForView->canAdjustSlotBalance()) {
            $this->addSlotsLink("slot-adjustment",
                "Slot adjustment",
                $this->url->fromRoute(AdjustmentController::ROUTE_NAME_START,
                    ['organisationId' => $this->organisationId])
            );
        }

        if($this->authorisationForView->canRefund()) {
            $this->addSlotsLink("slots-refund",
                "Refund slots",
                $this->url->fromRoute(RefundController::ROUTE_NAME,
                    ['organisationId' => $this->organisationId])
            );
        }
    }

    private function buildRelatedLinks()
    {
        if($this->authorisationForView->canViewTestLogs()) {
            $this->addRelatedLink("test-log",
                "Test logs",
                $this->url->fromRoute(MotTestLogController::ROUTE_INDEX, ['id' => $this->organisationId])
            );
        }

        if($this->authorisationForView->canViewEventHistory()) {
            $this->addRelatedLink("event-history",
                "Event history",
                $this->url->fromRoute(EventController::ROUTE_LIST, [
                    'id' => $this->organisationId,
                    'type' => EventController::TYPE_AE,
                ])
            );
        }
    }
}