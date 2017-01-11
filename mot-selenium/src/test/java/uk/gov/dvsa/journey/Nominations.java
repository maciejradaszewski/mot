package uk.gov.dvsa.journey;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.shared.MotUI;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.Notification;
import uk.gov.dvsa.ui.pages.OrgNotificationPage;
import uk.gov.dvsa.ui.pages.SiteNotificationPage;

import java.io.IOException;

public class Nominations {

    private PageNavigator pageNavigator;

    public Nominations(PageNavigator pageNavigator) {
        this.pageNavigator = pageNavigator;
    }

    public Notification viewMostRecent(User user) throws IOException {
        HomePage homePage = pageNavigator.gotoHomePage(user);
        Notification notification = homePage.clickOnNomination();

        if(notification.getNotificationText().contains("Authorised Examiner Delegate")) {
            return new OrgNotificationPage(pageNavigator.getDriver());
        }

        return new SiteNotificationPage(pageNavigator.getDriver());
    }

    public SiteNotificationPage viewOrderCardNotification(User user) throws IOException {
        HomePage homePage = pageNavigator.gotoHomePage(user);
        homePage.clickOrderCardNotificationLink();

        return new SiteNotificationPage(pageNavigator.getDriver());
    }

    public SiteNotificationPage viewActivateCardNotification(User user) throws IOException {
        HomePage homePage = pageNavigator.gotoHomePage(user);
        homePage.clickActivateCardNotificationLink();

        return new SiteNotificationPage(pageNavigator.getDriver());
    }

    public void orderAndActivateSecurityCard(User user) throws IOException {
        MotUI motUI = new MotUI(pageNavigator.getDriver());

        viewOrderCardNotification(user).clickOrderCard();
        motUI.authentication.securityCard.orderSecurityCardWithHomeAddress(user);
        viewActivateCardNotification(user).clickActivateCard();
        motUI.authentication.securityCard.activate2faCard(user);
    }
}
