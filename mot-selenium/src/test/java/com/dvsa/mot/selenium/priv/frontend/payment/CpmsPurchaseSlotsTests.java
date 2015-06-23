package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.*;
import org.testng.Assert;
import org.testng.annotations.Test;

import java.math.BigDecimal;

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

        Assert.assertTrue(authorisedExaminerOverviewPage.isBuySlotsLinkVisible(),
                "Verifying BuySlots link present");
        Assert.assertTrue(authorisedExaminerOverviewPage.isTransactionHistoryLinkVisible(),
                "Verifying TransactionHistory link present");
        Assert.assertTrue(authorisedExaminerOverviewPage.isSlotsUsageLinkVisible(),
                "Verifying SlotUsage link present");
        Assert.assertTrue(authorisedExaminerOverviewPage.isSetupDirectDebitLinkVisible(),
                "Verifying Setup DirectDebit Link present");
    }

    @Test(groups = {"Regression", "SPMS-37"}) public void purchaseSlotsByCardSuccessfulJourney() {
        PaymentConfirmationPage paymentConfirmationPage = loginAsAedmAndPurchaseSlotsByCard();

        Assert.assertTrue(paymentConfirmationPage.getStatusMessage().contains(
                        Assertion.ASSERTION_PURCHASE_SLOTS_BY_CARD_SUCCESS_MESSAGE.assertion),
                "Verifying Purchase Success Message");
        Assert.assertEquals(paymentConfirmationPage.getSlotsOrdered(),
                (Payments.VALID_PAYMENTS.slots + " slots"), "Verifying Slots Ordered");
        Assert.assertEquals(paymentConfirmationPage.getTotalCost(), ("£" + String.format("%.2f",
                (new BigDecimal(Payments.VALID_PAYMENTS.slots).multiply(Payments.COST_PER_SLOT)),
                "Verifying Total Cost")));
    }

    @Test(groups = {"Regression", "SPMS-37"})
    public void purchaseSlotsExceedingMaximumBalanceErrorTest() {
        BuySlotsPage buySlotsPage = AuthorisedExaminerOverviewPage
                .navigateHereFromLoginPage(driver, login.LOGIN_AEDM, Business.EXAMPLE_AE_INC)
                .clickBuySlotsLink().enterSlotsRequired(Payments.MAXIMUM_SLOTS.slots)
                .clickCalculateCostButtonInvalidSlots();

        Assert.assertTrue(buySlotsPage.isExceedsMaximumSlotBalanceMessageDisplayed(),
                "Verifying Maximum Slot Balance Exceeds Message displayed");
    }

    @Test(groups = {"Regression", "SPMS-88"}) public void purchaseSlotsUserCancelsPaymentTest() {
        BuySlotsPage buySlotsPage = AuthorisedExaminerOverviewPage
                .navigateHereFromLoginPage(driver, login.LOGIN_AEDM, Business.EXAMPLE_AE_INC)
                .clickBuySlotsLink().enterSlotsRequired(Payments.VALID_PAYMENTS.slots)
                .clickCalculateCostButton().clickPayByCardButton().clickCancelButton();

        Assert.assertTrue(buySlotsPage.isSlotsRequiredVisible(),
                "Verifying RequiredSlots field present");
        Assert.assertTrue(buySlotsPage.isCalculateCostButtonVisible(),
                "Verifying CalculateCost button present");
    }

    @Test(groups = {"Regression", "SPMS-47"}) public void transactionHistoryVerificationTest() {
        PaymentConfirmationPage paymentConfirmationPage = loginAsAedmAndPurchaseSlotsByCard();

        TransactionHistoryPage transactionHistoryPage =
                paymentConfirmationPage.clickBackToAuthorisedExaminerLink()
                        .clickTransactionHistoryLink();

        Assert.assertTrue(transactionHistoryPage.isNumberOfTransactionsDisplayed(),
                "Verifying NumberOfTransactions displayed");
        Assert.assertTrue(transactionHistoryPage.isTransactionsTableDisplayed(),
                "Verifying Transaction table is displayed");
        Assert.assertTrue(transactionHistoryPage.isDownloadPdfLinkDisplayed(),
                "Verifying PDF Link");
        Assert.assertTrue(transactionHistoryPage.isDownloadCsvLinkDisplayed(),
                "Verifying CSV Link");

        TransactionHistoryPage todayTransactionsHistory =
                transactionHistoryPage.clickTodayTransactionsLink();
        Assert.assertTrue(todayTransactionsHistory.isNumberOfTransactionsDisplayed(),
                "Verifying NumberOfTransactions displayed");
        Assert.assertTrue(todayTransactionsHistory.isTransactionsTableDisplayed(),
                "Verifying Transaction table is displayed");
        Assert.assertTrue(todayTransactionsHistory.isDownloadPdfLinkDisplayed(),
                "Verifying PDF Link");
        Assert.assertTrue(todayTransactionsHistory.isDownloadCsvLinkDisplayed(),
                "Verifying CSV Link");

        TransactionHistoryPage last7DaysTransactionsHistory =
                transactionHistoryPage.clickLast7DaysTransactionsLink();
        Assert.assertTrue(last7DaysTransactionsHistory.isNumberOfTransactionsDisplayed(),
                "Verifying NumberOfTransactions displayed");
        Assert.assertTrue(last7DaysTransactionsHistory.isTransactionsTableDisplayed(),
                "Verifying Transaction table is displayed");
        Assert.assertTrue(last7DaysTransactionsHistory.isDownloadPdfLinkDisplayed(),
                "Verifying PDF Link");
        Assert.assertTrue(last7DaysTransactionsHistory.isDownloadCsvLinkDisplayed(),
                "Verifying CSV Link");

        TransactionHistoryPage last30DaysTransactionsHistory =
                transactionHistoryPage.clickLast30DaysTransactionsLink();
        Assert.assertTrue(last30DaysTransactionsHistory.isNumberOfTransactionsDisplayed(),
                "Verifying NumberOfTransactions displayed");
        Assert.assertTrue(last30DaysTransactionsHistory.isTransactionsTableDisplayed(),
                "Verifying Transaction table is displayed");
        Assert.assertTrue(last30DaysTransactionsHistory.isDownloadPdfLinkDisplayed(),
                "Verifying PDF Link");
        Assert.assertTrue(last30DaysTransactionsHistory.isDownloadCsvLinkDisplayed(),
                "Verifying CSV Link");

        TransactionHistoryPage lastYearTransactionsHistory =
                transactionHistoryPage.clickLastYearTransactionsLink();
        Assert.assertTrue(lastYearTransactionsHistory.isNumberOfTransactionsDisplayed(),
                "Verifying NumberOfTransactions displayed");
        Assert.assertTrue(lastYearTransactionsHistory.isTransactionsTableDisplayed(),
                "Verifying Transaction table is displayed");
        Assert.assertTrue(lastYearTransactionsHistory.isDownloadPdfLinkDisplayed(),
                "Verifying PDF Link");
        Assert.assertTrue(lastYearTransactionsHistory.isDownloadCsvLinkDisplayed(),
                "Verifying CSV Link");
    }

    @Test(groups = {"Regression", "SPMS-47"}) public void paymentInvoiceDetailsVerificationTest() {
        PaymentConfirmationPage paymentConfirmationPage = loginAsAedmAndPurchaseSlotsByCard();
        PaymentDetailsPage paymentDetailsPage =
                paymentConfirmationPage.clickViewPurchaseDetailsLink();

        Assert.assertEquals(paymentDetailsPage.getSupplierDetailsText(), "Supplier details",
                "Verifying SupplierDetails present");
        Assert.assertEquals(paymentDetailsPage.getPurchaserDetailsText(), "Purchaser details",
                "Verifying PurchaserDetails present");
        Assert.assertEquals(paymentDetailsPage.getPaymentDetailsText(), "Payment details",
                "Verifying PaymentDetails present");
        Assert.assertEquals(paymentDetailsPage.getOrderDetailsText(), "Order details",
                "Verifying OrderDetails present");
        Assert.assertTrue(paymentDetailsPage.isPrintButtonPresent(),
                "Verifying Print button present");
    }

    @Test(groups = {"Regression", "SPMS-120"})
    public void financeUserPurchaseSlotsByChequeSuccessfulJourney() {
        ChequePaymentOrderConfirmedPage chequePaymentOrderConfirmedPage =
                loginAsFinanceUserAndPurchaseSlotsByCheque();

        Assert.assertEquals(chequePaymentOrderConfirmedPage.getStatusMessage(),
                Assertion.ASSERTION_FINANCE_USER_PURCHASE_SLOTS_BY_CHEQUE_SUCCESS_MESSAGE.assertion,
                "Verifying Finance User Purchase slots by Cheque Success Message");
        Assert.assertEquals(chequePaymentOrderConfirmedPage.getTotalSlotsOrdered(),
                (ChequePayment.VALID_CHEQUE_PAYMENTS.slots + " test slots"),
                "Verifying Total Slots Ordered");
        Assert.assertEquals(chequePaymentOrderConfirmedPage.getTotalCost(),
                ("£" + ChequePayment.VALID_CHEQUE_PAYMENTS.cost), "Verifying Total Cost");
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

        Assert.assertTrue(enterChequeDetailsPage.isValidationErrorMessageDisplayed(),
                "Verifying validation error displayed");
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

        Assert.assertEquals(searchedPaymentDetailsPage.getSupplierDetailsText(), "Supplier details",
                "Verifying SupplierDetails present");
        Assert.assertEquals(searchedPaymentDetailsPage.getPurchaserDetailsText(),
                "Purchaser details", "Verifying PurchaserDetails present");
        Assert.assertEquals(searchedPaymentDetailsPage.getPaymentDetailsText(), "Payment details",
                "Verifying PaymentDetails present");
        Assert.assertEquals(searchedPaymentDetailsPage.getOrderDetailsText(), "Order details",
                "Verifying OrderDetails present");
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

        Assert.assertEquals(searchedPaymentDetailsPage.getSupplierDetailsText(), "Supplier details",
                "Verifying SupplierDetails present");
        Assert.assertEquals(searchedPaymentDetailsPage.getPurchaserDetailsText(),
                "Purchaser details", "Verifying PurchaserDetails present");
        Assert.assertEquals(searchedPaymentDetailsPage.getPaymentDetailsText(), "Payment details",
                "Verifying PaymentDetails present");
        Assert.assertEquals(searchedPaymentDetailsPage.getOrderDetailsText(), "Order details",
                "Verifying OrderDetails present");
    }

}
