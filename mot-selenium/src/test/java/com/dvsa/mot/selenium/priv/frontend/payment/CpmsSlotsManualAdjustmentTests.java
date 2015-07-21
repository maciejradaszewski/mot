package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.ChequePayment;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.FinanceUserCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.DetailsOfAuthorisedExaminerPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.AdjustmentConfirmationPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.ManualAdjustmentSuccessPage;

import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsSlotsManualAdjustmentTests extends BaseTest {
    
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

    private DetailsOfAuthorisedExaminerPage loginAsFinanceUserAndSearchForAe(Login login, String aeRef) {
        DetailsOfAuthorisedExaminerPage detailsOfAuthorisedExaminerPage =
                SearchForAePage.navigateHereFromLoginPage(driver, login)
                        .searchForAeAndSubmit(aeRef);
        return detailsOfAuthorisedExaminerPage;
    }

    @Test(groups = {"Regression", "SPMS-143"})
    public void manualPositiveAdjustmentOfSlotsBalanceByFinanceUser() {
        String aeRef = createAeAndReturnAeReference("positiveManualAdjustment");
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        DetailsOfAuthorisedExaminerPage detailsOfAuthorisedExaminerPage =
                loginAsFinanceUserAndSearchForAe(financeUserLogin, aeRef);

        int slotsBeforeAdjustment =
                Integer.parseInt(detailsOfAuthorisedExaminerPage.getAeSlotBalance());
        detailsOfAuthorisedExaminerPage.clickLogout();

        AdjustmentConfirmationPage adjustmentConfirmationPage = AdjustmentConfirmationPage
                .loginAndCompleteManualPositiveAdjustmentOfSlotBalance(driver, financeUserLogin, aeRef);
                
        assertThat("Verifying Manual Adjustment of slots success message",
                adjustmentConfirmationPage.getManualAdjustmentStatusMessage(),
                is(Assertion.ASSERTION_MANUAL_ADJUSTMENT_OF_SLOTS_SUCCESS_MESSAGE.assertion));

        assertThat("Verifying adjusted slots balance message",
                adjustmentConfirmationPage.getAdjustedBalanceMessage(),
                is(("New slot balance for this Authorised Examiner is: " + (slotsBeforeAdjustment
                        + Payments.VALID_PAYMENTS.slots))));
    }

    @Test(groups = {"Regression", "SPMS-143"})
    public void manualNegativeAdjustmentOfSlotsBalanceByFinanceUser() {
        String aeRef = createAeAndReturnAeReference("negativeManualAdjustment");
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        DetailsOfAuthorisedExaminerPage detailsOfAuthorisedExaminerPage =
                loginAsFinanceUserAndSearchForAe(financeUserLogin, aeRef);

        int slotsBeforeAdjustment =
                Integer.parseInt(detailsOfAuthorisedExaminerPage.getAeSlotBalance());
        detailsOfAuthorisedExaminerPage.clickLogout();

        AdjustmentConfirmationPage adjustmentConfirmationPage = AdjustmentConfirmationPage
                .loginAndCompleteManualNegativeAdjustmentOfSlotBalance(driver, financeUserLogin, aeRef);

        assertThat("Verifying Manual Adjustment of slots success message",
                adjustmentConfirmationPage.getManualAdjustmentStatusMessage(),
                is(Assertion.ASSERTION_MANUAL_ADJUSTMENT_OF_SLOTS_SUCCESS_MESSAGE.assertion));
        
        assertThat("Verifying adjusted slots balance message",
                adjustmentConfirmationPage.getAdjustedBalanceMessage(),
                is(("New slot balance for this Authorised Examiner is: " + (slotsBeforeAdjustment
                        - Payments.VALID_PAYMENTS.slots))));
    }
    
    @Test(groups = {"Regression", "SPMS-80"})
    public void manualAdjustmentOfTransactionForWrongAe() {
        String aeRef1 = createAeAndReturnAeReference("ChequePayment");
        String aeRef2 = createAeAndReturnAeReference("ManualAdjustment");
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        
        ManualAdjustmentSuccessPage manualAdjustmentSuccessPage = ManualAdjustmentSuccessPage
                .loginAndAdjustPaymentForWrongAe(driver, financeUserLogin, aeRef1, ChequePayment.VALID_CHEQUE_PAYMENTS, aeRef2);
        
        assertThat("Verifying Adjustment Success Message",
                manualAdjustmentSuccessPage.getAdjustmentStatusMessage(), is("Transaction has been adjusted"));
    }
    
    @Test(groups = {"Regression", "SPMS-80"})
    public void manualAdjustmentOfTransactionForInvalidPaymentData() {
        String aeRef = createAeAndReturnAeReference("ChequePayment");
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        ManualAdjustmentSuccessPage manualAdjustmentSuccessPage = ManualAdjustmentSuccessPage
                .loginAndAdjustPaymentForInvalidPaymentData(driver, financeUserLogin, aeRef, ChequePayment.VALID_CHEQUE_PAYMENTS, "410.00");
        
        assertThat("Verifying Adjustment Success Message",
                manualAdjustmentSuccessPage.getAdjustmentStatusMessage(), is("Transaction has been adjusted"));
    }

}
