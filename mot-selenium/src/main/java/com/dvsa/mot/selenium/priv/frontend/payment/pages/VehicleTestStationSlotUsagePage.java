package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class VehicleTestStationSlotUsagePage extends BasePage {

    private static final String PAGE_TITLE = "TEST SLOT USAGE";

    @FindBy(id = "transactionHistoryTable") private WebElement slotUsageTable;

    @FindBy(id = "downloadFile") private WebElement downloadFiles;

    public VehicleTestStationSlotUsagePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public boolean isSlotUsageTableDisplayed() {
        return slotUsageTable.isDisplayed();
    }

    public boolean isDownloadFileOptionsDisplayed() {
        return downloadFiles.isDisplayed();
    }

}
