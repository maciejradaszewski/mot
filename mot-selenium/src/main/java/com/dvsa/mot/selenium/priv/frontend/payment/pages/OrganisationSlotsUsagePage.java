package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.PageInteractionHelper;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import java.util.List;

/**
 * Slots organisation usage page.
 */
public class OrganisationSlotsUsagePage extends BasePage {

    private static final String PAGE_TITLE = "TEST SLOT USAGE";

    @FindBy(id = "summaryLine") private WebElement numberOfSlotsUsed;

    @FindBy(id = "transactionHistoryTable") private WebElement slotUsageTable;

    @FindBy(id = "downloadFile") private WebElement downloadFiles;

    @FindBy(id = "today") private WebElement slotsUsedToday;

    @FindBy(id = "last7Days") private WebElement slotsUsedLast7Days;

    @FindBy(id = "last30Days") private WebElement slotsUsedLast30Days;

    @FindBy(id = "lastYear") private WebElement slotsUsedLastYear;

    @FindBy(xpath = "//tbody/tr/td[1]/a") protected WebElement vtsNumber;

    public OrganisationSlotsUsagePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public boolean isNumberOfSlotsUsedDisplayed() {
        return numberOfSlotsUsed.isDisplayed();
    }

    public String getNumberOfSlotsUsed() {
        return numberOfSlotsUsed.getText();
    }

    public boolean isSlotUsageTableDisplayed() {
        PageInteractionHelper interactionHelper = new PageInteractionHelper(driver);
        List<WebElement> table =
                interactionHelper.findElementWithoutImplicitWaits(By.id("transactionHistoryTable"));
        return (table.size() > 0);
    }

    public boolean isDownloadFileOptionsDisplayed() {
        return downloadFiles.isDisplayed();
    }

    public OrganisationSlotsUsagePage filterSlotsUsedToday() {
        slotsUsedToday.click();
        return new OrganisationSlotsUsagePage(driver);
    }

    public OrganisationSlotsUsagePage filterSlotsUsedLast7days() {
        slotsUsedLast7Days.click();
        return new OrganisationSlotsUsagePage(driver);
    }

    public OrganisationSlotsUsagePage filterSlotsUsedLast30days() {
        slotsUsedLast30Days.click();
        return new OrganisationSlotsUsagePage(driver);
    }

    public OrganisationSlotsUsagePage filterSlotsUsedLastYear() {
        slotsUsedLastYear.click();
        return new OrganisationSlotsUsagePage(driver);
    }

    public VehicleTestStationSlotUsagePage clickVtsNumber() {
        vtsNumber.click();
        return new VehicleTestStationSlotUsagePage(driver);
    }

}
