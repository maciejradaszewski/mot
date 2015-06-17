package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.SpecialNotice;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class SpecialNoticePreviewPage extends BasePage {

    public static final String PAGE_TITLE = "SPECIAL NOTICE PREVIEW";

    @FindBy(id = "publish-special-notice") private WebElement publishSpecialNoticeButton;

    @FindBy(id = "back_create_special_notice") private WebElement goBack;

    @FindBy(id = "info-message") private WebElement specialNoticeCreatedMessage;

    @FindBy(id = "special-notice-content-title") private WebElement title;

    @FindBy(id = "special-notice-content") private WebElement subjectMessage;

    public static SpecialNoticePreviewPage navigateHereFromLoginPage(WebDriver driver, Login login,
            SpecialNotice specialNotice) {
        return CreateSpecialNoticePage.navigateHereFromLoginPage(driver, login)
                .enterSpecialNotice(specialNotice).submit();
    }

    public SpecialNoticePreviewPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public SpecialNoticesPage publishSpecialNotice() {
        publishSpecialNoticeButton.click();
        return new SpecialNoticesPage(driver);
    }

    public CreateSpecialNoticePage goBack() {
        goBack.click();
        return new CreateSpecialNoticePage(driver);
    }

    public String  getSpecialNoticeCreatedMessage() {
        return specialNoticeCreatedMessage.getText();
    }

    public String getSubjectMessage() {
        return subjectMessage.getText();
    }

    public String getTitle() {
        return title.getText();
    }

    public boolean isPublishSpecialNoticeDisplayed() {
        return isElementDisplayed(publishSpecialNoticeButton);
    }

}
