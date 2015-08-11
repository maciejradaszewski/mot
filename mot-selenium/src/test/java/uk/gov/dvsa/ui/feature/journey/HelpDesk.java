package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.helper.RandomDataGenerator;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.helpdesk.HelpDeskUserProfilePage;

import java.io.IOException;

public class HelpDesk extends BaseTest {

    @Test
    public void successfullyUpdateAUsersEmailAddress() throws IOException {
        User csco = userData.createCustomerServiceOfficer(false);
        User bob = userData.createAedm(false);
        String email = RandomDataGenerator.generateEmail(15);

        //Given that I am on Bob's profile page as a Customer Service Centre Operative
        HelpDeskUserProfilePage helpDeskUserProfilePage =
                pageNavigator.goToUserHelpDeskProfilePage(csco, bob.getId());

        //When I update Bob's email address
        helpDeskUserProfilePage.updateEmailSuccessfully(email);

        //Then Bob's email is updated successfully
        helpDeskUserProfilePage.isEmailUpdateSuccessful(email);
    }
}
