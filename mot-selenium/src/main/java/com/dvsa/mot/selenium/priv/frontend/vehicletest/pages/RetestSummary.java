package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import org.openqa.selenium.WebDriver;

public class RetestSummary extends TestSummary {

    private static String RETEST_PAGE_TITLE = "MOT RE-TEST COMPLETE";

    public RetestSummary(WebDriver driver) {
        super(driver);
    }

    public MOTTestResultPageTestCompletePage clickFinishPrint() {
        disablePrintingOnCurrentPage();
        finishAndPrintButton.click();
        return new MOTTestResultPageTestCompletePage(driver, RETEST_PAGE_TITLE);
    }

    public MOTTestResultPageTestCompletePage clickFinishPrint(String passCode) {
        enterNewPasscode(passCode);
        disablePrintingOnCurrentPage();
        finishAndPrintButton.click();
        return new MOTTestResultPageTestCompletePage(driver, RETEST_PAGE_TITLE);
    }

}
