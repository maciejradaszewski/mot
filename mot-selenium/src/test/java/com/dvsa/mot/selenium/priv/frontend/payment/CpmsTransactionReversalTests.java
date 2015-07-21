package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.ChequePayment;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.FinanceUserCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.PaymentConfirmationPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.PaymentDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.TransactionReversalConfirmationPage;

import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsTransactionReversalTests extends BaseTest {
    
    private Login createFinanceUserReturnFinanceUserLogin() {
        FinanceUserCreationApi financeUserCreationApi = new FinanceUserCreationApi();
        Login financeUserLogin = financeUserCreationApi.createFinanceUser().getLogin();
        return financeUserLogin;
    }

    @Test(groups = {"Regression", "SPMS-42"}) public void transactionReversalCardPayment() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("paymentReversalCardPayment");
        String aeRef = aeDetails.getAeRef();
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();

        PaymentConfirmationPage paymentCompletePage = PaymentConfirmationPage
                .purchaseSlotsByCardSuccessfully(driver, aedmLogin, Payments.VALID_PAYMENTS);
        assertThat("Verifying Purchase Success Message", paymentCompletePage.getStatusMessage()
                        .contains(Assertion.ASSERTION_PURCHASE_SLOTS_BY_CARD_SUCCESS_MESSAGE.assertion),
                is(true));
        paymentCompletePage.clickLogout();

        TransactionReversalConfirmationPage transactionReversalConfirmationPage = TransactionReversalConfirmationPage
                .navigateHereFromLoginAndReverseCardPayment(driver, financeUserLogin, aeRef);
        assertThat("Verifying Successful reversal message",
                transactionReversalConfirmationPage.getReversalSuccessfulMessage(),
                is("The transaction has been successfully reversed"));

        PaymentDetailsPage paymentDetailsPageAfterReversal =
                transactionReversalConfirmationPage.clickReturnToTransactionDetailsLink();
        assertThat("Verifying status confirmation message",
                paymentDetailsPageAfterReversal.getTransactionStatusMessage(),
                is("This payment has been reversed"));

    }

    @Test(groups = {"Regression", "SPMS-42"}) public void transactionReversalChequePayment() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("paymentReversalChequePayment");
        String aeRef = aeDetails.getAeRef();
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        
        TransactionReversalConfirmationPage transactionReversalConfirmationPage = TransactionReversalConfirmationPage
                .navigateHereFromLoginAndReverseChequePayment(driver, financeUserLogin, aeRef, ChequePayment.VALID_CHEQUE_PAYMENTS);

        assertThat("Verifying Successful reversal message",
                transactionReversalConfirmationPage.getReversalSuccessfulMessage(),
                is("The transaction has been successfully reversed"));

        PaymentDetailsPage paymentDetailsPageAfterReversal =
                transactionReversalConfirmationPage.clickReturnToTransactionDetailsLink();
        assertThat("Verifying status confirmation message",
                paymentDetailsPageAfterReversal.getTransactionStatusMessage(),
                is("This payment has been reversed"));
    }

}
