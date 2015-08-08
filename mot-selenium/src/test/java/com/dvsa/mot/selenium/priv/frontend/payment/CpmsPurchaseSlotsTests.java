package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.FinanceUserCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.*;

import org.testng.annotations.Test;

import java.math.BigDecimal;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.is;

public class CpmsPurchaseSlotsTests extends BaseTest {
    
    private Login createAedmAndReturnAedmLogin(String prefix) {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe(prefix);
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        return aedmLogin;
    }
    
    private String createAeAndReturnAeReference(String prefix) {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe(prefix);
        String aeRef = aeDetails.getAeRef();
        return aeRef;
    }
    
    private Login createFinanceUserReturnFinanceUserLogin() {
        FinanceUserCreationApi financeUserCreationApi = new FinanceUserCreationApi();
        Login financeUserLogin = financeUserCreationApi.createFinanceUser().getLogin();
        return financeUserLogin;
    }

    private PaymentConfirmationPage loginAsAedmAndPurchaseSlotsByCard() {
        Login aedmLogin = createAedmAndReturnAedmLogin("PurchaseSlotsByCard");

        PaymentConfirmationPage paymentConfirmationPage = PaymentConfirmationPage
                .purchaseSlotsByCardSuccessfully(driver, aedmLogin, Payments.VALID_PAYMENTS);
        return paymentConfirmationPage;
    }

    private ChequePaymentOrderConfirmedPage loginAsFinanceUserAndPurchaseSlotsByCheque() {
        String aeRef = createAeAndReturnAeReference("ChequePayment");
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();

        ChequePaymentOrderConfirmedPage chequePaymentOrderConfirmedPage =
                ChequePaymentOrderConfirmedPage
                        .purchaseSlotsByChequeSuccessfully(driver, financeUserLogin, aeRef,
                                ChequePayment.VALID_CHEQUE_PAYMENTS);
        return chequePaymentOrderConfirmedPage;
    }

    @Test(groups = {"Regression", "SPMS-37"})
    public void purchaseSlotsAuthorizedExaminerPageVerification() {
        Login aedmLogin = createAedmAndReturnAedmLogin("AePageVerification");
        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin);

        assertThat("Verifying BuySlots link present",
                authorisedExaminerOverviewPage.isBuySlotsLinkVisible(), is(true));
        assertThat("Verifying TransactionHistory link present",
                authorisedExaminerOverviewPage.isTransactionHistoryLinkVisible(), is(true));
        assertThat("Verifying SlotUsage link present",
                authorisedExaminerOverviewPage.isSlotsUsageLinkVisible(), is(true));
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
        Login aedmLogin = createAedmAndReturnAedmLogin("ExceedMaximumSlotBalance");
        BuySlotsPage buySlotsPage = BuySlotsPage.navigateToBuySlotsPageFromLogin(driver, aedmLogin)
                .enterSlotsRequired(Payments.MAXIMUM_SLOTS.slots)
                .clickCalculateCostButtonInvalidSlots();

        assertThat("Verifying Maximum Slot Balance Exceeds Message displayed",
                buySlotsPage.isExceedsMaximumSlotBalanceMessageDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-88"}) public void purchaseSlotsUserCancelsPaymentTest() {
        Login aedmLogin = createAedmAndReturnAedmLogin("UserCancelsPayment");
        BuySlotsPage buySlotsPage = AuthorisedExaminerOverviewPage
                .navigateHereFromLoginPage(driver, aedmLogin)
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
        PaymentDetailsPage paymentDetailsPage = loginAsAedmAndPurchaseSlotsByCard()
                .clickViewPurchaseDetailsLink();       

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
    public void financeUserPurchaseSlotsByInvalidDateCheque() {
        String aeRef = createAeAndReturnAeReference("ChequePayment");
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();

        EnterChequeDetailsPage enterChequeDetailsPage = EnterChequeDetailsPage
                .navigateToChequeDetailsPageFromLogin(driver, financeUserLogin, aeRef)
                .enterInvalidChequeDate()
                .enterValidChequeInformation(ChequePayment.VALID_CHEQUE_PAYMENTS)
                .clickCreateOrderButtonWithInvalidDetails();

        assertThat("Verifying validation error displayed",
                enterChequeDetailsPage.isValidationErrorMessageDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-199"})
    public void financeUserSearchForPaymentByPaymentReference() {
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        PaymentDetailsPage paymentDetailsPage = loginAsAedmAndPurchaseSlotsByCard()
                .clickViewPurchaseDetailsLink();
        String paymentReference = paymentDetailsPage.getReceiptReference();
        paymentDetailsPage.clickLogout();

        PaymentDetailsPage searchedPaymentDetailsPage =
                PaymentSearchPage.navigateHereFromLoginPage(driver, financeUserLogin)
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
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        PaymentDetailsPage paymentDetailsPage = loginAsAedmAndPurchaseSlotsByCard()
                .clickViewPurchaseDetailsLink();
        String invoiceReference = paymentDetailsPage.getInvoiceReference();
        paymentDetailsPage.clickLogout();

        PaymentDetailsPage searchedPaymentDetailsPage =
                PaymentSearchPage.navigateHereFromLoginPage(driver, financeUserLogin)
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
