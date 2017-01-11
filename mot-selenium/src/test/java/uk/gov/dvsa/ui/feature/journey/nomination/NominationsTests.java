package uk.gov.dvsa.ui.feature.journey.nomination;

import org.testng.annotations.Test;
import ru.yandex.qatools.allure.annotations.Description;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.shared.role.OrganisationBusinessRoleCodes;
import uk.gov.dvsa.domain.shared.role.TradeRoles;
import uk.gov.dvsa.ui.DslTest;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;

@Description("Nomination notifications signposts nominees to next step")
public class NominationsTests extends DslTest {

    @Test(testName = "2fa", groups = {"BVT"})
    void non2faUserCanOrderCardViaNominationsNotificationLink() throws IOException {
        step("Given I have been nominated for a Site Manager role as non 2fa user");
        User not2faActiveUser = userData.createUserWithoutRole();
        motApi.nominations.nominateSiteRole(not2faActiveUser, siteData.createSite().getId(), TradeRoles.SITE_MANAGER);

        step("And I have not ordered a card");
        step("When I order a card from Site Manager nomination notification");
        motUI.nominations.viewMostRecent(not2faActiveUser).clickOrderCard();
        String message = motUI.authentication.securityCard.orderSecurityCardWithHomeAddress(not2faActiveUser);

        step("Then the order is successful");
        assertThat("The card is ordered successfully", message, containsString("Your security card has been ordered"));
    }

    @Test(testName = "2fa", groups = {"BVT"})
    void non2faUserCanActivateCardViaNominationNotificationsLink() throws IOException {
        step("Given I have been nominated for a Site Manager role as non 2fa user");
        User orderedCardUser = userData.createUserWithoutRole();
        motApi.nominations.nominateSiteRole(orderedCardUser, siteData.createSite().getId(), TradeRoles.SITE_MANAGER);

        step("And I have ordered a card");
        motUI.authentication.securityCard.orderSecurityCardWithHomeAddress(orderedCardUser);

        step("When I activate the card from Site Manager nomination notification");
        motUI.nominations.viewActivateCardNotification(orderedCardUser).clickActivateCard();
        String message = motUI.authentication.securityCard.activate2faCard(orderedCardUser).getConfirmationText();

        step("Then my card is activated");
        assertThat("Activation Successful", message, containsString("Your security card has been activated"));
    }

    @Test(testName = "2fa", groups = {"BVT"})
    void non2faUserCannotOrderCardTwiceViaNotificationsLink() throws IOException {
        step("Given I have previously ordered a card as non 2fa user from notification");
        User not2faActiveUser = userData.createUserWithoutRole();
        motApi.nominations.nominateSiteRole(not2faActiveUser, siteData.createSite().getId(), TradeRoles.SITE_MANAGER);
        motUI.authentication.securityCard.orderSecurityCardWithHomeAddress(not2faActiveUser);

        step("When I try to order another card from notification list");
        String message = motUI.nominations.viewOrderCardNotification(not2faActiveUser).clickOrderCardExpectingAlreadyOrderedText();

        step("Then I am redirected to already ordered card page");
        assertThat("Already ordered Page is Shown", message, containsString("You have already ordered a security card"));
    }

    @Test(testName ="2fa", groups = {"BVT"})
    void aedmNon2faUserNominatedWithNoCardDirectedToOrderACard() throws Exception {
        step("Given I am not 2fa and I am nominated for an AEDM role");
        User not2faActiveUser = userData.createUserWithoutRole();
        motApi.nominations.nominateOrganisationRoleWithRoleCode(not2faActiveUser, aeData.createAeWithDefaultValues().getId(), OrganisationBusinessRoleCodes.AEDM);

        step("When I am nominated, I am asked to order a card");
        //assert that order card notification is generated.
        String message = motUI.nominations.viewOrderCardNotification(not2faActiveUser).getNotificationText();
        assertThat("Notification for AEDM - prompted to order a security card is shown", message, containsString("You need to order a security card."));
    }

    @Test(testName = "2fa", groups = {"BVT"})
    void aedmNon2faUserNominatedWithCardDirectedToActivateCard() throws Exception {
        step("Given I am not 2fa and I am nominated for an AEDM role");
        User orderedCardUser = userData.createUserWithoutRole();
        motApi.nominations.nominateOrganisationRoleWithRoleCode(orderedCardUser, aeData.createAeWithDefaultValues().getId(), OrganisationBusinessRoleCodes.AEDM);

        step("When I have ordered a card");
        motUI.authentication.securityCard.orderSecurityCardWithHomeAddress(orderedCardUser);

        step("When I am nominated, I recieve a notification to activate my card");
        String message = motUI.nominations.viewActivateCardNotification(orderedCardUser).getNotificationText();
        assertThat("Notification for AEDM - prompted to activate a security card", message, containsString("once you have activated the card."));
    }

    @Test(testName = "2fa", groups = {"BVT"})
    void aedmNominationNotificationsAreSentAfterCardActivation() throws Exception {
        step("Given I am not 2fa and I am nominated for an AEDM role");
        User orderedCardUser = userData.createUserWithoutRole();
        motApi.nominations.nominateOrganisationRoleWithRoleCode(orderedCardUser, aeData.createAeWithDefaultValues().getId(), OrganisationBusinessRoleCodes.AEDM);

        step("And I have ordered and activated a card");
        motUI.authentication.securityCard.orderSecurityCardWithHomeAddress(orderedCardUser);
        motUI.authentication.securityCard.activate2faCard(orderedCardUser);

        step("I receive a notification advising me that I have been assigned the role of AEDM");
        String message = motUI.nominations.viewMostRecent(orderedCardUser).getNotificationText();
        assertThat("Notification for AEDM - assigned the role of AEDM", message, containsString("You have been assigned a role of Authorised Examiner Designated Manager"));
    }

    @Test(testName = "2fa", groups = {"BVT"})
    void aedmNominationNotificationsAreSentWithoutOrderingACardForAnExistingTradeUser() throws Exception {
        step("Given I am a non-2fa trade user with a role");
        User nominee = userData.createTester(siteData.createSite().getId(), false);

        step("When I have been nominated for an AEDM role");
        motApi.nominations.nominateOrganisationRoleWithRoleCode(nominee, aeData.createAeWithDefaultValues().getId(), OrganisationBusinessRoleCodes.AEDM);

        step("Then I receive a notification advising me I have been automatically assigned the AEDM role");
        String message = motUI.nominations.viewMostRecent(nominee).getNotificationText();
        assertThat("Notification for AEDM - assigned the AEDM role", message, containsString("You have been assigned a role of Authorised Examiner Designated Manager"));
    }

    @Test(testName = "non-2fa", groups = {"BVT"})
    void aedmNominationNotificationsAreSentImmediatelyWhen2faFeatureIsDisabled() throws Exception {
        step("Given I am not 2fa and I am nominated for an AEDM role");
        User user = userData.createUserWithoutRole();
        motApi.nominations.nominateOrganisationRoleWithRoleCode(user, aeData.createAeWithDefaultValues().getId(), OrganisationBusinessRoleCodes.AEDM);

        step("I receive a notification advising me that I have been assigned the role of AEDM");
        String message = motUI.nominations.viewMostRecent(user).getNotificationText();
        assertThat("Notification for AEDM - assigned the role of AEDM", message, containsString("You have been assigned a role of Authorised Examiner Designated Manager"));
    }

    @Test(testName = "2fa", groups = {"BVT"})
    public void tradeUserReceivesNotificationWhenCscoOrdersCardOnTheirBehalf() throws IOException {
        step("Given I have been nominated for a Site Manager role as non 2fa user");
        User tradeUser = userData.createUserWithoutRole();
        motApi.nominations.nominateSiteRole(tradeUser, siteData.createSite().getId(), TradeRoles.SITE_MANAGER);

        step("When a CSCO orders a card on my behalf");
        motUI.authentication.securityCard.orderCardForTradeUserAsCSCO(userData.createCSCO(), tradeUser);

        step("Then I receive an activate notification on my homepage");
        motUI.nominations.viewActivateCardNotification(tradeUser);
    }
}
