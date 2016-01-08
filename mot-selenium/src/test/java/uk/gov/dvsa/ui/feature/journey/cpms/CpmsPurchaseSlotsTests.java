package uk.gov.dvsa.ui.feature.journey.cpms;

import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AedmAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.FinanceAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.cpms.BuyTestSlotsPage;
import uk.gov.dvsa.ui.pages.cpms.CardPaymentConfirmationPage;
import uk.gov.dvsa.ui.pages.cpms.ChoosePaymentTypePage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.core.Is.is;

public class CpmsPurchaseSlotsTests extends BaseTest {

    @Test (groups = {"BVT", "Regression"}, description = "SPMS-37 Purachase slots by card successfully", dataProvider = "createAedmAndAe")
    public void purchaseSlotsByCardSuccessfully(User aedm, AeDetails aeDetails) throws IOException, URISyntaxException {
        
      //Given I am on Buy test slots page as an Aedm
      BuyTestSlotsPage buyTestSlotsPage = pageNavigator
                .goToPageAsAuthorisedExaminer(aedm, AedmAuthorisedExaminerViewPage.class, AedmAuthorisedExaminerViewPage.PATH, aeDetails.getId())
                .clickBuySlotsLink();
      
      //When I submit the card payment request with required slots & valid card details
      CardPaymentConfirmationPage cardPaymentConfirmationPage = buyTestSlotsPage
              .enterSlotsRequired(50000)
              .clickCalculateCostButton()
              .clickContinueToPay()
              .enterCardDetails()
              .clickPayNowButton();
      
      //Then the payment should be successful
      assertThat("Verifying payment successful message is displayed",
              cardPaymentConfirmationPage.isPaymentSuccessfulMessageDisplayed(), is(true));
      assertThat("Payment status message verification",
              cardPaymentConfirmationPage.getPaymentStatusMessage(), containsString("Payment has been successful"));   
    }
    
    @Test (groups = {"BVT", "Regression"}, description = "SPMS-37 Purachase slots exceeding maximun balance", dataProvider = "createAedmAndAe")
    public void purchaseSlotsExceedingMaximumBalanceErrorTest(User aedm, AeDetails aeDetails) throws IOException, URISyntaxException {
        
      //Given I am on Buy test slots page as an Aedm with positive slot balance
      pageNavigator.goToPageAsAuthorisedExaminer(aedm, AedmAuthorisedExaminerViewPage.class, AuthorisedExaminerViewPage.PATH, aeDetails.getId())
                .clickBuySlotsLink()
                .enterSlotsRequired(60000)
                .clickCalculateCostButton()
                .clickContinueToPay()
                .enterCardDetails()
                .clickPayNowButton();
      
      BuyTestSlotsPage buyTestSlotsPage = pageNavigator
              .goToPageAsAuthorisedExaminer(aedm, AedmAuthorisedExaminerViewPage.class, AuthorisedExaminerViewPage.PATH, aeDetails.getId())
              .clickBuySlotsLink();
              
      //When I submit required slots which makes the slot balance exceed the maximum balance
      BuyTestSlotsPage buyTestSlotsPageWithError = buyTestSlotsPage
              .enterSlotsRequired(75000)
              .clickCalculateCostButtonWithExcessSlots();
      
      //Then the payment should not be processed with validation message
      assertThat("Verifying validation message for exceeding slot balance",
              buyTestSlotsPageWithError.isExceedsMaximumSlotBalanceMessageDisplayed(), is(true));   
    }
    
    @Test (groups = {"BVT", "Regression"}, description = "SPMS-264 Finance user processes Card payment", dataProvider = "createFinanceUserAndAe")
    public void financeUserProcessesCardPayment(User financeUser, AeDetails aeDetails) throws IOException, URISyntaxException {
        
      //Given I am on Choose payment type page as a Finance user
        ChoosePaymentTypePage choosePaymentTypePage = pageNavigator
                .goToPageAsAuthorisedExaminer(financeUser, FinanceAuthorisedExaminerViewPage.class, FinanceAuthorisedExaminerViewPage.PATH, aeDetails.getId())
                .clickBuySlotsLinkAsFinanceUser();
      
     //When I submit the card payment request with required slots & valid card details
      CardPaymentConfirmationPage cardPaymentConfirmationPage = choosePaymentTypePage
              .selectCardPaymentTypeAndSubmit()
              .enterSlotsRequired(50000)
              .clickCalculateCostButton()
              .clickContinueToPay()
              .enterCardDetails()
              .clickPayNowButton();
      
      //Then the payment should be successful
      assertThat("Verifying payment successful message is displayed",
              cardPaymentConfirmationPage.isPaymentSuccessfulMessageDisplayed(), is(true));
      assertThat("Payment status message verification",
              cardPaymentConfirmationPage.getPaymentStatusMessage(), containsString("Payment has been successful"));
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
