package uk.gov.dvsa.ui.feature.journey;

import java.io.IOException;

import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class CpmsPurchaseSlotsTests extends BaseTest {

    @Test (groups = {"BVT", "Regression"}, description = "SPMS-37 Purachase slots by card successfully", dataProvider = "createAedmAndAe")
    public void purchaseSlotsByCardSuccessfully(User aedm, AeDetails aeDetails) throws IOException {
        
        //Given I am on Buy test slots page as an Aedm
        motUI.cpms.purchaseSlots.navigateToBuyTestSlotsPageAsAedm(aedm, String.valueOf(aeDetails.getId()));
      
        //When I submit valid card payment details with required slots
        motUI.cpms.purchaseSlots.submitPaymentDetailsWithRequiredSlots("10000");
      
        //Then the payment should be successful
        assertThat(motUI.cpms.purchaseSlots.cardPaymentConfirmationPage.isPaymentSuccessfulMessageDisplayed(), is(true));
    }
    
    @Test (groups = {"BVT", "Regression"}, description = "SPMS-37 Purachase slots exceeding maximun balance", dataProvider = "createAedmAndAe")
    public void purchaseSlotsExceedingMaximumBalanceErrorTest(User aedm, AeDetails aeDetails) throws IOException {
        
        //Given The organisation has positive slot balance
        motUI.cpms.purchaseSlots.userProcessesCardPaymentSuccessfully(aedm, String.valueOf(aeDetails.getId()), "60000");

        //And I am on Buy test slots page
        motUI.cpms.purchaseSlots.goToBuyTestSlotsPage(aedm, String.valueOf(aeDetails.getId()));
              
        //When I request slots which makes the slot balance exceed the maximum balance
        motUI.cpms.purchaseSlots.submitSlotsWhichExceedsMaximumSlotBalance("75000");
      
        //Then the payment should not be processed and display validation message
        assertThat(motUI.cpms.purchaseSlots.buyTestSlotsPage.isExceedsMaximumSlotBalanceMessageDisplayed(), is(true));
    }
    
    @Test (groups = {"BVT", "Regression"}, description = "SPMS-264 Finance user processes Card payment", dataProvider = "createFinanceUserAndAe")
    public void financeUserProcessesCardPayment(User financeUser, AeDetails aeDetails) throws IOException {

        //Given I am on Buy test slots page as a Finance user
        motUI.cpms.purchaseSlots.navigateToBuyTestSlotsPageAsFinanceUser(financeUser, String.valueOf(aeDetails.getId()));
      
        //When I submit valid card payment details with required slots
        motUI.cpms.purchaseSlots.submitPaymentDetailsWithRequiredSlots("10000");
      
        //Then the payment should be successful
        assertThat(motUI.cpms.purchaseSlots.cardPaymentConfirmationPage.isPaymentSuccessfulMessageDisplayed(), is(true));
    }

    @Test (groups = {"BVT", "Regression"}, description = "SPMS-88 User cancels Card payment", dataProvider = "createAedmAndAe")
    public void userCancelsCardPayment(User aedm, AeDetails aeDetails) throws IOException {

        //Given I am on Card details page with valid slot purchase request
        motUI.cpms.purchaseSlots.navigateToCardDetailsPage(aedm, String.valueOf(aeDetails.getId()));

        //When I Cancel the card payment
        motUI.cpms.purchaseSlots.userCancelsCardPayment();

        //Then I should be navigated to Buy test slots page
        assertThat(motUI.cpms.purchaseSlots.buyTestSlotsPage.isCalculateCostButtonDisplayed(), is(true));
    }
    
    @DataProvider(name = "createAedmAndAe")
    public Object[][] createAedmAndAe() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        User aedm = userData.createAedm(aeDetails.getId(), "My_AEDM", false);
        return new Object[][]{{aedm, aeDetails}};
    }
    
    @DataProvider(name = "createFinanceUserAndAe")
    public Object[][] createFinanceUserAndAe() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        User financeUser = userData.createAFinanceUser("Finance", false);
        return new Object[][]{{financeUser, aeDetails}};
    }
}
