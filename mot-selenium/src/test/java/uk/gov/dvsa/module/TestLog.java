package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.DateRange;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.helper.enums.DateRangeFilter;
import uk.gov.dvsa.ui.pages.vts.SiteTestLogPage;

import java.io.IOException;

public class TestLog {

    private PageNavigator pageNavigator = null;
    private SiteTestLogPage testLogPage;

    public TestLog(PageNavigator pageNavigator) {
        this.pageNavigator = pageNavigator;
    }

    public void siteLogPage(User user, String siteId) throws IOException {
       testLogPage = pageNavigator.gotoSiteTestLogPage(user, siteId);
    }

    public boolean isDisplayed() {
        return testLogPage.isDataTableDisplayed();
    }

    public void home() {
        testLogPage.returnToVts();
    }

    public boolean isSelected(DateRangeFilter filter) {
        return testLogPage.isSelected(filter);
    }

    public void selectDateRange(DateRange range1, DateRange range2) {
        testLogPage.enterCustomDateRange(range1, range2);
    }

    public int getNumberOfMotTestInTable(){
        return testLogPage.getNumberOfRows();
    }
}
