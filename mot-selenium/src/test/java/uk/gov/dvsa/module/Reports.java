package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.cpms.DownloadReportPage;
import uk.gov.dvsa.ui.pages.cpms.GenerateReportPage;

import java.io.IOException;

public class Reports {

    private PageNavigator pageNavigator;
    public DownloadReportPage downloadReportPage;

    public Reports(PageNavigator pageNavigator)
    {
        this.pageNavigator = pageNavigator;
    }

    public GenerateReportPage goToGenerateReportPage(User user) throws IOException {
        return pageNavigator.goToGenerateReportPage(user);
    }

    public DownloadReportPage generateFinancialReport(String report) throws IOException {
        GenerateReportPage generateReportPage = new GenerateReportPage(pageNavigator.getDriver());

        downloadReportPage = generateReportPage
                .selectReportType(report).clickGenerateReportButton();
        return downloadReportPage;
    }
}
