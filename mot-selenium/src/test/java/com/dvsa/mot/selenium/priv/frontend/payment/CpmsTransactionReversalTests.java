package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.ChequePayment;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.ChequePaymentOrderConfirmedPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.PaymentConfirmationPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.PaymentDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.TransactionReversalConfirmationPage;
import org.testng.Assert;
import org.testng.annotations.Test;

public class CpmsTransactionReversalTests extends BaseTest {

    @Test(groups = {"slice_A", "SPMS-42"}) public void transactionReversalCardPayment() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("paymentReversal");
        String aeRef = aeDetails.getAeRef();
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);

        PaymentConfirmationPage paymentCompletePage = PaymentConfirmationPage
                .purchaseSlotsByCardSuccessfully(driver, aedmLogin, Payments.VALID_PAYMENTS);
        Assert.assertTrue(paymentCompletePage.getStatusMessage().contains(
                        Assertion.ASSERTION_PURCHASE_SLOTS_BY_CARD_SUCCESS_MESSAGE.assertion),
                "Verifying Purchase Success Message");
        paymentCompletePage.clickLogout();

        TransactionReversalConfirmationPage transactionReversalConfirmationPage = PaymentDetailsPage
                .navigateHereFromTransactionHistoryPage(driver, login.LOGIN_FINANCE_USER, aeRef)
                .clickReverseThisPaymentButton().clickConfirmReverseButton();
        Assert.assertEquals(transactionReversalConfirmationPage.getReversalSuccessfulMessage(),
                "The transaction has been successfully reversed",
                "Verifying Successful reversal message");

        PaymentDetailsPage paymentDetailsPageAfterReversal =
                transactionReversalConfirmationPage.clickReturnToTransactionDetailsLink();
        Assert.assertEquals(paymentDetailsPageAfterReversal.getTransactionStatusMessage(),
                "This payment has been reversed", "Verifying status confirmation message");

    }

    @Test(groups = {"slice_A", "SPMS-42"}) public void transactionReversalChequePayment() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("paymentReversal");
        String aeRef = aeDetails.getAeRef();

        ChequePaymentOrderConfirmedPage chequePaymentOrderConfirmedPage =
                ChequePaymentOrderConfirmedPage
                        .purchaseSlotsByChequeSuccessfully(driver, login.LOGIN_FINANCE_USER, aeRef,
                                ChequePayment.VALID_CHEQUE_PAYMENTS);
        Assert.assertEquals(chequePaymentOrderConfirmedPage.getStatusMessage(),
                Assertion.ASSERTION_FINANCE_USER_PURCHASE_SLOTS_BY_CHEQUE_SUCCESS_MESSAGE.assertion,
                "Verifying Finance User Purchase slots by Cheque Success Message");

        TransactionReversalConfirmationPage transactionReversalConfirmationPage =
                chequePaymentOrderConfirmedPage.clickViewPurchaseDetailsLink()
                        .clickReverseThisPaymentButton().clickConfirmReverseButton();
        Assert.assertEquals(transactionReversalConfirmationPage.getReversalSuccessfulMessage(),
                "The transaction has been successfully reversed",
                "Verifying Successful reversal message");

        PaymentDetailsPage paymentDetailsPageAfterReversal =
                transactionReversalConfirmationPage.clickReturnToTransactionDetailsLink();
        Assert.assertEquals(paymentDetailsPageAfterReversal.getTransactionStatusMessage(),
                "This payment has been reversed", "Verifying status confirmation message");
    }

}
