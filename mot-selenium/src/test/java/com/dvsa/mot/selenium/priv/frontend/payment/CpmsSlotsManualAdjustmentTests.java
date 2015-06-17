package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Business;
import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.DetailsOfAuthorisedExaminerPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.AdjustmentConfirmationPage;
import org.testng.Assert;
import org.testng.annotations.Test;

public class CpmsSlotsManualAdjustmentTests extends BaseTest {

    private DetailsOfAuthorisedExaminerPage loginAsFinanceUserAndSearchForAe() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("ManualSlotsAdjustment");
        String aeRef = aeDetails.getAeRef();
        DetailsOfAuthorisedExaminerPage detailsOfAuthorisedExaminerPage =
                SearchForAePage.navigateHereFromLoginPage(driver, login.LOGIN_FINANCE_USER)
                        .searchForAeAndSubmit(aeRef);
        return detailsOfAuthorisedExaminerPage;
    }

    @Test(groups = {"slice_A", "SPMS-143"})
    public void manualPositiveAdjustmentOfSlotsBalanceByFinanceUser() {
        DetailsOfAuthorisedExaminerPage detailsOfAuthorisedExaminerPage =
                loginAsFinanceUserAndSearchForAe();

        int slotsBeforeAdjustment =
                Integer.parseInt(detailsOfAuthorisedExaminerPage.getAeSlotBalance());

        AdjustmentConfirmationPage adjustmentConfirmationPage =
                detailsOfAuthorisedExaminerPage.clickSlotsAdjustmentLinkAsFinanceUser()
                        .enterSlotsToBeAdjusted(Payments.VALID_PAYMENTS.slots)
                        .selectPositiveAdjustment().selectReason("Refund").clickAdjustButton();

        Assert.assertEquals(adjustmentConfirmationPage.getManualAdjustmentStatusMessage(),
                Assertion.ASSERTION_MANUAL_ADJUSTMENT_OF_SLOTS_SUCCESS_MESSAGE.assertion,
                "Verifying Manual Adjustment of slots success message");
        Assert.assertEquals(adjustmentConfirmationPage.getAdjustedBalanceMessage(),
                ("New slot balance for this AE is: " + (slotsBeforeAdjustment
                        + Payments.VALID_PAYMENTS.slots)),
                "Verifying adjusted slots balance message");
    }

    @Test(groups = {"slice_A", "SPMS-143"})
    public void manualNegativeAdjustmentOfSlotsBalanceByFinanceUser() {
        DetailsOfAuthorisedExaminerPage detailsOfAuthorisedExaminerPage =
                loginAsFinanceUserAndSearchForAe();

        int slotsBeforeAdjustment =
                Integer.parseInt(detailsOfAuthorisedExaminerPage.getAeSlotBalance());

        AdjustmentConfirmationPage adjustmentConfirmationPage =
                detailsOfAuthorisedExaminerPage.clickSlotsAdjustmentLinkAsFinanceUser()
                        .enterSlotsToBeAdjusted(Payments.VALID_PAYMENTS.slots)
                        .selectNegativeAdjustment().selectReason("Failed payment")
                        .clickAdjustButton();

        Assert.assertEquals(adjustmentConfirmationPage.getManualAdjustmentStatusMessage(),
                Assertion.ASSERTION_MANUAL_ADJUSTMENT_OF_SLOTS_SUCCESS_MESSAGE.assertion,
                "Verifying Manual Adjustment of slots success message");
        Assert.assertEquals(adjustmentConfirmationPage.getAdjustedBalanceMessage(),
                ("New slot balance for this AE is: " + (slotsBeforeAdjustment
                        - Payments.VALID_PAYMENTS.slots)),
                "Verifying adjusted slots balance message");
    }

    @Test(groups = {"slice_A", "SPMS-143"})
    public void verifyingSlotsAdjustmentLinkUnavailableForAedm() {

        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, login.LOGIN_AEDM,
                        Business.EXAMPLE_AE_INC);

        Assert.assertTrue(authorisedExaminerOverviewPage.isSetupDirectDebitLinkVisible(),
                "Verifying Setup Direct Debit link present for AEDM");
        Assert.assertFalse(authorisedExaminerOverviewPage.isSlotsAdjustmentLinkVisible(),
                "Verifying Slots Adjustment link is not present for AEDM");
    }

}
