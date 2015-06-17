package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.FinancialReportDownloadPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.testng.Assert;
import org.testng.annotations.Test;

public class CpmsFinancialReportsTests extends BaseTest {

    private FinancialReportDownloadPage generateFinancialReports(Login login, String reportType,
            String reportTitle) {
        FinancialReportDownloadPage financialReportDownloadPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login)
                        .clickGeneralFinancialReportsLink().selectReportType(reportType)
                        .clickGenerateReportButton(reportTitle);
        return financialReportDownloadPage;
    }

    @Test(groups = {"slice_A", "SPMS-137"})
    public void generateFinancialReportForAllPaymentsTest() {

        FinancialReportDownloadPage financialReportDownloadPage =
                generateFinancialReports(Login.LOGIN_FINANCE_USER, "All Payments",
                        "All payments report");

        Assert.assertTrue(financialReportDownloadPage.isBackToGenerateReportLinkDisplayed(),
                "Verifying Back to generate report link displayed");
    }

    @Test(groups = {"slice_A", "SPMS-138"})
    public void generateFinancialReportForTransactionBreakdown() {

        FinancialReportDownloadPage financialReportDownloadPage =
                generateFinancialReports(Login.LOGIN_FINANCE_USER, "Transaction Breakdown",
                        "Transaction breakdown report");

        Assert.assertTrue(financialReportDownloadPage.isBackToGenerateReportLinkDisplayed(),
                "Verifying Back to generate report link displayed");
    }

}
