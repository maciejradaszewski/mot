package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.VtsNumberEntryPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class AbortMotTestPage extends BasePage {

    public static final String PAGE_TITLE = "ABORT MOT TEST";
    private WebElement link;

    @FindBy(id = "reasonForAbort") private WebElement reasonForAbortion;

    @FindBy(id = "mot_test_abort_confirm") private WebElement confirmReason;

    @FindBy(id = "return_mot_test_summary") private WebElement returnToSummaryScreen;

    @FindBy(id = "sln-action-abort") private WebElement abortButton;

    @FindBy(id = "reasonForCancel-1") private WebElement reasonToAbort;

    public AbortMotTestPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public AbortMotTestPage enterReasonForAborting(String reason) {
        reasonForAbortion.sendKeys(reason);
        return this;
    }

    public VtsNumberEntryPage confirmReasonForAborting() {
        confirmReason.click();
        return new VtsNumberEntryPage(driver);
    }
}
