package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ForgotPwdSecurityQuesOnePage extends BasePage {
    private String PAGE_TITLE = "MOT TESTING SERVICE\n" + "FORGOTTEN PASSWORD\n" + "STEP 2 OF 3";

    @FindBy(className = "lede") private WebElement beforePasswordChangedMessage;

    @FindBy(id = "legendQuestion") private WebElement legendQuestion;

    @FindBy(id = "question1") private WebElement question1;

    @FindBy(id = "submitSecurityQuestion") private WebElement submitSecurityQuestion;

    @FindBy(id = "validation-summary-message") private WebElement validationSummaryMessage;

    @FindBy(className = "validation-message") private WebElement validationMessage;

    public ForgotPwdSecurityQuesOnePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public ForgotPwdSecurityQuesOnePage(WebDriver driver, String PAGE_TITLE) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getBeforePwdChangeMsg() {
        return beforePasswordChangedMessage.getText();
    }

    public String getLegendQuestion() {
        return legendQuestion.getText();
    }

    public ForgotPwdSecurityQuesOnePage submitInvalidAnswer(String answer) {
        question1.clear();
        question1.sendKeys(answer);
        submitSecurityQuestion.click();
        return new ForgotPwdSecurityQuesOnePage(driver);
    }

    public ForgotSecurityQuestionsPage submitInvalidAnswer3(String answer) {
        question1.clear();
        question1.sendKeys(answer);
        submitSecurityQuestion.click();
        return new ForgotSecurityQuestionsPage(driver);
    }

    public ForgotPwdSecurityQuesTwoPage submitValidAnswer(String answer) {
        question1.clear();
        question1.sendKeys(answer);
        submitSecurityQuestion.click();
        return new ForgotPwdSecurityQuesTwoPage(driver);
    }

    public ForgotPwdSecurityQuesTwoPage submitValidAnswer(String answer, String title) {
        question1.clear();
        question1.sendKeys(answer);
        submitSecurityQuestion.click();
        return new ForgotPwdSecurityQuesTwoPage(driver, title);
    }

    public String getValidationSummaryMessage() {
        return validationSummaryMessage.getText();
    }

    public String getValidationMessage(){
        return validationMessage.getText();
    }
}
