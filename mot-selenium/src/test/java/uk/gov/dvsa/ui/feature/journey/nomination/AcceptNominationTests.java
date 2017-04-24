package uk.gov.dvsa.ui.feature.journey.nomination;

import org.testng.annotations.Test;
import ru.yandex.qatools.allure.annotations.Description;
import ru.yandex.qatools.allure.annotations.Features;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.shared.role.OrganisationBusinessRoleCodes;
import uk.gov.dvsa.domain.shared.role.TradeRoles;
import uk.gov.dvsa.ui.DslTest;
import uk.gov.dvsa.ui.pages.ArchiveNotificationPage;
import uk.gov.dvsa.ui.pages.InboxNotificationPage;
import uk.gov.dvsa.ui.pages.Notification;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.is;

@Description("This Journey does not include nominations for AEDM role")
@Features("Nomination notifications for nominees to accept or reject role")
public class AcceptNominationTests extends DslTest {

    @Test(groups = {"nomination"})
    void userCannotAcceptNominationWith2faOn() throws IOException {
        step("Given I have been nominated for a Site Manager role as non 2fa user");
        User not2faActiveUser = motApi.user.createUserWithoutRole();
        motApi.nominations.nominateSiteRole(not2faActiveUser, siteData.createSite().getId(), TradeRoles.SITE_MANAGER);

        step("When I viewMostRecent my notifications");

        step("Then I cannot accept the nomination");
        assertThat("Accept Nomination Button is not displayed",
            motUI.nominations.viewMostRecent(not2faActiveUser).isAcceptButtonDisplayed(), is(false));
    }

    @Test(groups = {"nomination"})
    void userCanAcceptSiteManagerNominationWith2faOn() throws IOException {
        step("Given I have been nominated for a Site Manager role as non 2fa user");
        User user = motApi.user.createUserWithoutRole();
        motApi.nominations.nominateSiteRole(user, siteData.createSite().getId(), TradeRoles.SITE_MANAGER);

        step("When I order and activate the card from Site Manager nomination notification");
        motUI.nominations.orderAndActivateSecurityCard(user);
        String message = motUI.nominations.viewMostRecent(user).acceptNomination().getConfirmationText();

        step("Then I can accept my Site Manager nomination");

        assertThat("Nominated was accepted successfully",
                message, containsString("You have accepted the role of Site manager"));

    }

    @Test(groups = {"nomination"})
    void userCanAcceptAedNominationWith2faOn() throws IOException {
        step("Given I have been nominated for a AED role as non 2fa user");
        User user = motApi.user.createUserWithoutRole();
        motApi.nominations.nominateOrganisationRoleWithRoleCode(user, aeData.createAeWithDefaultValues().getId(), OrganisationBusinessRoleCodes.AED);

        step("When I order and activate the card from AED nomination notification");
        motUI.nominations.orderAndActivateSecurityCard(user);
        String message = motUI.nominations.viewMostRecent(user).acceptNomination().getConfirmationText();

        step("Then I can accept my AED nomination");

        assertThat("Authorised Examiner Delegate Role Confirmation",
                message, containsString("You have accepted the role of Authorised Examiner Delegate"));
    }

    @Test(testName = "2faHardStopDisabled", groups = {"2fa"})
    void existingTradeUserCanAcceptSiteAdminNominationWithoutOrdering2faCard() throws IOException {
        step("Given I am a trade user nominated as a site admin as a non 2fa user");
        User nominee = motApi.user.createTester(siteData.createSite().getId(), false);
        motApi.nominations.nominateSiteRole(nominee, siteData.createSite().getId(), TradeRoles.SITE_ADMIN);

        step("When I accept the nomination without ordering or activating a 2fa card");
        String message = motUI.nominations.viewMostRecent(nominee).acceptNomination().getConfirmationText();

        step("Then I am given the site admin role");
        assertThat("Site Admin Role Confirmation", message, containsString("You have accepted the role of Site admin"));
    }

    @Test(testName = "2faHardStopDisabled", groups = {"2fa"})
    void existingTradeUserCanAcceptAedNominationWithoutOrdering2faCard() throws IOException {
        step("Given I am trade user nominated for an AED role as a non 2fa user");
        User nominee = motApi.user.createTester(siteData.createSite().getId(), false);
        motApi.nominations.nominateOrganisationRoleWithRoleCode(nominee, aeData.createAeWithDefaultValues().getId(), OrganisationBusinessRoleCodes.AED);

        step("When I accept the nomination without ordering or activating a 2fa card");
        String message = motUI.nominations.viewMostRecent(nominee).acceptNomination().getConfirmationText();

        step("Then I can accept my AED nomination");
        assertThat("Authorised Examiner Delegate Role Confirmation",
                message, containsString("You have accepted the role of Authorised Examiner Delegate"));
    }

    @Test(testName = "2faHardStopDisabled", groups = {"2fa"})
    void existingTradeUserCanAcceptSiteManagerNominationWithoutActivating2faCard() throws IOException {
        step("Given I am a trade user nominated as a site manager");
        User nominee = motApi.user.createNon2FaTester(siteData.createSite().getId(), false);
        motApi.nominations.nominateSiteRole(nominee, siteData.createSite().getId(), TradeRoles.SITE_MANAGER);

        step("When I accept the nomination without activating a 2fa card");
        String message = motUI.nominations.viewMostRecent(nominee).acceptNomination().getConfirmationText();

        step("Then I am given the site manager role");
        assertThat("Site Manager Role Confirmation", message, containsString("You have accepted the role of Site manager"));
    }

    @Test(groups = {"nomination"})
    void tradeUserAcceptTesterNominationAndArchiveIt() throws IOException {
        step("Given I am nominated as a tester");
        User nominee = motApi.user.createTester(siteData.createSite().getId());
        motApi.nominations.nominateSiteRole(nominee,siteData.createSite().getId(), TradeRoles.TESTER);

        step("And I accept the nomination");
        Notification notification = motUI.nominations.viewMostRecent(nominee).acceptNomination();
        String message = notification.getConfirmationText();
        String title = notification.getTitle();
        assertThat("Tester Role Confirmation", message, containsString("You have accepted the role of Tester"));

        step("When I archive this notification");
        InboxNotificationPage inboxNotificationPage = notification.archiveNomination();
        assertThat("Inbox is empty", 0 == inboxNotificationPage.countNotifications());

        step("Then notification is archived");
        ArchiveNotificationPage archiveNotificationPage = inboxNotificationPage.clickArchiveTab();
        assertThat("Notification was archived successfully", archiveNotificationPage.hasNotification(title));
    }

    @Test(groups = {"nomination"})
    void tradeUserAcceptSiteAdminNomination() throws IOException {
        step("Given I nominated as a site manager");
        User nominee = motApi.user.createSiteAdmin(siteData.createSite().getId(), false);
        motApi.nominations.nominateSiteRole(nominee,siteData.createSite().getId(), TradeRoles.SITE_ADMIN);

        step("When I accept the nomination");
        String message = motUI.nominations.viewMostRecent(nominee).acceptNomination().getConfirmationText();

        step("Then I am given the site admin role");
        assertThat("Site Admin Role Confirmation", message, containsString("You have accepted the role of Site admin"));
    }

    @Test(groups = {"nomination"})
    void tradeUserAcceptSiteManagerNomination() throws IOException {
        step("Given I nominate a user as a site manager");
        User nominee = motApi.user.createSiteManager(siteData.createSite().getId(), false);
        motApi.nominations.nominateSiteRole(nominee,siteData.createSite().getId(), TradeRoles.SITE_MANAGER);
        
        step("When I accept the nomination");
        String message = motUI.nominations.viewMostRecent(nominee).acceptNomination().getConfirmationText();

        step("Then I am given the site manager role");
        assertThat("Site Manager Role Confirmation", message, containsString("You have accepted the role of Site manager"));
    }
}
