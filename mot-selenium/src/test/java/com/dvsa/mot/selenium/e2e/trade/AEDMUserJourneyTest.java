package com.dvsa.mot.selenium.e2e.trade;

import com.dvsa.mot.selenium.datasource.DateRange;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.AedmTestLogs;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class AEDMUserJourneyTest extends BaseTest {

    @Test(groups = {"E2E", "slice_A"}) public void testAedmEntersInvalidDateRange() {
        Login aedmLogin =
                createAEDM(createAE("testAedmEntersInvalidDateRange"), Login.LOGIN_AREA_OFFICE2,
                        false);

        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin);
        AedmTestLogs aedmTestLogs = authorisedExaminerOverviewPage.viewTestLogs();
        aedmTestLogs.setFromWhichDay(DateRange.PUBLIC_HOLIDAY.startDay);
        aedmTestLogs.setFromWhichMonth(DateRange.PUBLIC_HOLIDAY.startMonth);
        aedmTestLogs.setFromWhichYear(DateRange.PUBLIC_HOLIDAY.startYear);
        aedmTestLogs.setToWhichDay(DateRange.PUBLIC_HOLIDAY.endDay);
        aedmTestLogs.setToWhichMonth(DateRange.PUBLIC_HOLIDAY.endMonth);
        aedmTestLogs.setToWhichYear(DateRange.PUBLIC_HOLIDAY.endYear);
        aedmTestLogs.downloadCsvReport();

        assertThat(ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

}
