package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.Configurator;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpDeskUserProfilePage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.VtsAbortMotTestPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.DuplicateReplacementCertificateSearchPage;
import org.openqa.selenium.WebDriver;

import java.text.MessageFormat;

/*This class is created only for the tests to be re-directed to certain urls to check if they can be accessed or not */


public class GoToTheUrl extends Configurator {

    public static AuthorisedExaminerOverviewPage goToTheAeOverviewPage(WebDriver driver, int aeId) {
        driver.get(baseUrl() + "/authorised-examiner/" + aeId);
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public static AuthorisedExaminerOverviewPage goToAedmOverviewPage(WebDriver driver) {
        driver.get(baseUrl() + "/vehicle-search");
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public static DuplicateReplacementCertificateSearchPage goToDuplicateReplacementCertificateSearchPage(
            WebDriver driver) {
        driver.get(baseUrl() + "/replacement-certificate-vehicle-search");
        return new DuplicateReplacementCertificateSearchPage(driver);
    }

    public static DuplicateReplacementCertificateSearchPage goToDuplicateReplacementCertificateSearchPage(
            WebDriver driver, String title) {
        driver.get(baseUrl() + "/replacement-certificate-vehicle-search");
        return new DuplicateReplacementCertificateSearchPage(driver, title);
    }

    public static VtsAbortMotTestPage goToVtsAbortTestPage(WebDriver driver, String testNumber) {
        driver.get(MessageFormat
                .format("{0}/mot-test/{1}/reason-for-aborting", baseUrl(), testNumber));
        return new VtsAbortMotTestPage(driver);
    }

    public static HelpDeskUserProfilePage goToHelpdeskUserProfilePage(WebDriver driver,
            String profileId) {
        driver.get(baseUrl() + "/user-admin/user-profile/" + profileId);
        return new HelpDeskUserProfilePage(driver);
    }

    public static UserDashboardPage goToCreateNewVehicleRecordVehicleIdentificationPage(
            WebDriver driver, Login login) {
        driver.get(baseUrl() + "/vehicle-step/add-step-one" + login);
        return new UserDashboardPage(driver);

    }
}
