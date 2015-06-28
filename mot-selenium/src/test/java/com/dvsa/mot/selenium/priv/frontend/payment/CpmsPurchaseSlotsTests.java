package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.*;
import org.testng.annotations.Test;

import java.math.BigDecimal;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.is;

public class CpmsPurchaseSlotsTests extends BaseTest {

    private PaymentConfirmationPage loginAsAedmAndPurchaseSlotsByCard() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("PurchaseSlotsByCard");
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);

        PaymentConfirmationPage paymentConfirmationPage = PaymentConfirmationPage
                .purchaseSlotsByCardSuccessfully(driver, aedmLogin, Payments.VALID_PAYMENTS);
        return paymentConfirmationPage;
    }

    private ChequePaymentOrderConfirmedPage loginAsFinanceUserAndPurchaseSlotsByCheque() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("ChequePayment");
        String aeRef = aeDetails.getAeRef();

        ChequePaymentOrderConfirmedPage chequePaymentOrderConfirmedPage =
                ChequePaymentOrderConfirmedPage
                        .purchaseSlotsByChequeSuccessfully(driver, login.LOGIN_FINANCE_USER, aeRef,
                                ChequePayment.VALID_CHEQUE_PAYMENTS);
        return chequePaymentOrderConfirmedPage;
    }

    @Test(groups = {"Regression", "SPMS-37"})
    public void purchaseSlotsAuthorizedExaminerPageVerification() {
        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, login.LOGIN_AEDM,
                        Business.EXAMPLE_AE_INC);

        assertThat("Verifying BuySlots link present",
                authorisedExaminerOverviewPage.isBuySlotsLinkVisible(), is(true));
        assertThat("Verifying TransactionHistory link present",
                authorisedExaminerOverviewPage.isTransactionHistoryLinkVisible(), is(true));
        assertThat("Verifying SlotUsage link present",
                authorisedExaminerOverviewPage.isSlotsUsageLinkVisible(), is(true));
        assertThat("Verifying Setup DirectDebit Link present",
                authorisedExaminerOverviewPage.isSetupDirectDebitLinkVisible(), is(true));
        assertThat("Verifying Slots Adjustment link is not present for AEDM",
                authorisedExaminerOverviewPage.isSlotsAdjustmentLinkVisible(), is(false));
    }

    @Test(groups = {"Regression", "SPMS-37"}) public void purchaseSlotsByCardSuccessfulJourney() {
        PaymentConfirmationPage paymentConfirmationPage = loginAsAedmAndPurchaseSlotsByCard();

        assertThat("Verifying Purchase Success Message", paymentConfirmationPage.getStatusMessage(),
                containsString(
                        Assertion.ASSERTION_PURCHASE_SLOTS_BY_CARD_SUCCESS_MESSAGE.assertion));
        assertThat("Verifying Slots Ordered", paymentConfirmationPage.getSlotsOrdered(),
                is(Payments.VALID_PAYMENTS.slots + " slots"));
        assertThat("Verifying Total Cost displayed", paymentConfirmationPage.getTotalCost(),
                is("£" + String.format("%.2f", (new BigDecimal(Payments.VALID_PAYMENTS.slots)
                        .multiply(Payments.COST_PER_SLOT)))));
    }

    @Test(groups = {"Regression", "SPMS-37"})
    public void purchaseSlotsExceedingMaximumBalanceErrorTest() {
        BuySlotsPage buySlotsPage = AuthorisedExaminerOverviewPage
                .navigateHereFromLoginPage(driver, login.LOGIN_AEDM, Business.EXAMPLE_AE_INC)
                .clickBuySlotsLink().enterSlotsRequired(Payments.MAXIMUM_SLOTS.slots)
                .clickCalculateCostButtonInvalidSlots();

        assertThat("Verifying Maximum Slot Balance Exceeds Message displayed",
                buySlotsPage.isExceedsMaximumSlotBalanceMessageDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-88"}) public void purchaseSlotsUserCancelsPaymentTest() {
        BuySlotsPage buySlotsPage = AuthorisedExaminerOverviewPage
                .navigateHereFromLoginPage(driver, login.LOGIN_AEDM, Business.EXAMPLE_AE_INC)
                .clickBuySlotsLink().enterSlotsRequired(Payments.VALID_PAYMENTS.slots)
                .clickCalculateCostButton().clickPayByCardButton().clickCancelButton();

        assertThat("Verifying RequiredSlots field present", buySlotsPage.isSlotsRequiredVisible(),
                is(true));
        assertThat("Verifying CalculateCost button present",
                buySlotsPage.isCalculateCostButtonVisible(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-47"}) public void transactionHistoryVerificationTest() {
        PaymentConfirmationPage paymentConfirmationPage = loginAsAedmAndPurchaseSlotsByCard();

        TransactionHistoryPage transactionHistoryPage =
                paymentConfirmationPage.clickBackToAuthorisedExaminerLink()
                        .clickTransactionHistoryLink();

        assertThat("Verifying Number of purchases",
                transactionHistoryPage.getNumberOfTransactionsText(),
                is("1 purchase in the last 7 days"));
        assertThat("Verifying Transaction table is displayed",
                transactionHistoryPage.isTransactionsTableDisplayed(), is(true));
        assertThat("Verifying download file options displayed",
                transactionHistoryPage.isDownloadFileOptionsDisplayed(), is(true));

        TransactionHistoryPage todayTransactionsHistory =
                transactionHistoryPage.clickTodayTransactionsLink();
        assertThat("Verifying Number of purchases - Today",
                todayTransactionsHistory.getNumberOfTransactionsText(), is("1 purchase today"));
        assertThat("Verifying Transaction table is displayed",
                todayTransactionsHistory.isTransactionsTableDisplayed(), is(true));
        assertThat("Verifying download file options displayed",
                todayTransactionsHistory.isDownloadFileOptionsDisplayed(), is(true));

        TransactionHistoryPage last7DaysTransactionsHistory =
                transactionHistoryPage.clickLast7DaysTransactionsLink();
        assertThat("Verifying Number of purchases - 7days",
                last7DaysTransactionsHistory.getNumberOfTransactionsText(),
                is("1 purchase in the last 7 days"));
        assertThat("Verifying Transaction table is displayed",
                last7DaysTransactionsHistory.isTransactionsTableDisplayed(), is(true));
        assertThat("Verifying download file options displayed",
                last7DaysTransactionsHistory.isDownloadFileOptionsDisplayed(), is(true));

        TransactionHistoryPage last30DaysTransactionsHistory =
                transactionHistoryPage.clickLast30DaysTransactionsLink();
        assertThat("Verifying Number of purchases - 30days",
                last30DaysTransactionsHistory.getNumberOfTransactionsText(),
                is("1 purchase in the last 30 days"));
        assertThat("Verifying Transaction table is displayed",
                last30DaysTransactionsHistory.isTransactionsTableDisplayed(), is(true));
        assertThat("Verifying download file options displayed",
                last30DaysTransactionsHistory.isDownloadFileOptionsDisplayed(), is(true));

        TransactionHistoryPage lastYearTransactionsHistory =
                transactionHistoryPage.clickLastYearTransactionsLink();
        assertThat("Verifying Number of purchases - Lastyear",
                lastYearTransactionsHistory.getNumberOfTransactionsText(),
                is("1 purchase in the last year"));
        assertThat("Verifying Transaction table is displayed",
                lastYearTransactionsHistory.isTransactionsTableDisplayed(), is(true));
        assertThat("Verifying download file options displayed",
                lastYearTransactionsHistory.isDownloadFileOptionsDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-47"}) public void paymentInvoiceDetailsVerificationTest() {
        PaymentConfirmationPage paymentConfirmationPage = loginAsAedmAndPurchaseSlotsByCard();
        PaymentDetailsPage paymentDetailsPage =
                paymentConfirmationPage.clickViewPurchaseDetailsLink();

        assertThat("Verifying SupplierDetails displayed",
                paymentDetailsPage.getSupplierDetailsText(), is("Supplier details"));
        assertThat("Verifying PurchaserDetails displayed",
                paymentDetailsPage.getPurchaserDetailsText(), is("Purchaser details"));
        assertThat("Verifying PaymentDetails displayed", paymentDetailsPage.getPaymentDetailsText(),
                is("Payment details"));
        assertThat("Verifying OrderDetails displayed", paymentDetailsPage.getOrderDetailsText(),
                is("Order details"));
        assertThat("Verifying Print button present", paymentDetailsPage.isPrintButtonDisplayed(),
                is(true));
    }

    @Test(groups = {"Regression", "SPMS-120"})
    public void financeUserPurchaseSlotsByChequeSuccessfulJourney() {
        ChequePaymentOrderConfirmedPage chequePaymentOrderConfirmedPage =
                loginAsFinanceUserAndPurchaseSlotsByCheque();

        assertThat("Verifying Purchase Success Message",
                chequePaymentOrderConfirmedPage.getStatusMessage(),
                is(Assertion.ASSERTION_FINANCE_USER_PURCHASE_SLOTS_BY_CHEQUE_SUCCESS_MESSAGE.assertion));
        assertThat("Verifying Total Slots Ordered",
                chequePaymentOrderConfirmedPage.getTotalSlotsOrdered(),
                is(ChequePayment.VALID_CHEQUE_PAYMENTS.slots + " test slots"));
        assertThat("Verifying Total Cost", chequePaymentOrderConfirmedPage.getTotalCost(),
                is("£" + ChequePayment.VALID_CHEQUE_PAYMENTS.cost));
    }

    @Test(groups = {"Regression", "SPMS-120"})
    public void financeUserPurchaseSlotsByExcessAmountCheque() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("ChequePayment");
        String aeRef = aeDetails.getAeRef();

        EnterChequeDetailsPage enterChequeDetailsPage =
                SearchForAePage.navigateHereFromLoginPage(driver, login.LOGIN_FINANCE_USER)
                        .searchForAeAndSubmit(aeRef).clickBuySlotsLinkAsFinanceUser()
                        .selectChequePaymentType().clickStartOrder()
                        .enterChequeDetails(ChequePayment.EXCESS_CHEQUE_PAYMENTS)
                        .clickCreateOrderButtonWithExcessAmount();

        assertThat("Verifying validation error displayed",
                enterChequeDetailsPage.isValidationErrorMessageDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-199"})
    public void financeUserSearchForPaymentByPaymentReference() {

        PaymentConfirmationPage paymentConfirmationPage = loginAsAedmAndPurchaseSlotsByCard();
        PaymentDetailsPage paymentDetailsPage =
                paymentConfirmationPage.clickViewPurchaseDetailsLink();
        String paymentReference = paymentDetailsPage.getReceiptReference();
        paymentDetailsPage.clickLogout();

        PaymentDetailsPage searchedPaymentDetailsPage =
                PaymentSearchPage.navigateHereFromLoginPage(driver, login.LOGIN_FINANCE_USER)
                        .selectPaymentReference().enterReferenceAndSubmitSearch(paymentReference)
                        .clickReferenceLink(paymentReference);

        assertThat("Verifying SupplierDetails displayed",
                searchedPaymentDetailsPage.getSupplierDetailsText(), is("Supplier details"));
        assertThat("Verifying PurchaserDetails displayed",
                searchedPaymentDetailsPage.getPurchaserDetailsText(), is("Purchaser details"));
        assertThat("Verifying PaymentDetails displayed",
                searchedPaymentDetailsPage.getPaymentDetailsText(), is("Payment details"));
        assertThat("Verifying OrderDetails displayed",
                searchedPaymentDetailsPage.getOrderDetailsText(), is("Order details"));
    }

    @Test(groups = {"Regression", "SPMS-77"})
    public void financeUserSearchForPaymentByInvoiceReference() {

        PaymentConfirmationPage paymentConfirmationPage = loginAsAedmAndPurchaseSlotsByCard();
        PaymentDetailsPage paymentDetailsPage =
                paymentConfirmationPage.clickViewPurchaseDetailsLink();
        String invoiceReference = paymentDetailsPage.getInvoiceReference();
        paymentDetailsPage.clickLogout();

        PaymentDetailsPage searchedPaymentDetailsPage =
                PaymentSearchPage.navigateHereFromLoginPage(driver, login.LOGIN_FINANCE_USER)
                        .selectInvoiceReference().enterReferenceAndSubmitSearch(invoiceReference)
                        .clickReferenceLink(invoiceReference);

        assertThat("Verifying SupplierDetails displayed",
                searchedPaymentDetailsPage.getSupplierDetailsText(), is("Supplier details"));
        assertThat("Verifying PurchaserDetails displayed",
                searchedPaymentDetailsPage.getPurchaserDetailsText(), is("Purchaser details"));
        assertThat("Verifying PaymentDetails displayed",
                searchedPaymentDetailsPage.getPaymentDetailsText(), is("Payment details"));
        assertThat("Verifying OrderDetails displayed",
                searchedPaymentDetailsPage.getOrderDetailsText(), is("Order details"));
    }

}
