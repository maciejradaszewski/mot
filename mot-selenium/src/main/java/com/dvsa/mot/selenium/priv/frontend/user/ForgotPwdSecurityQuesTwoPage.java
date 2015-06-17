package com.dvsa.mot.selenium.priv.frontend.user;


import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ForgotPwdSecurityQuesTwoPage extends BasePage {
    private String PAGE_TITLE = "MOT TESTING SERVICE\n" + "FORGOTTEN PASSWORD\n" + "STEP 3 OF 3";

    @FindBy(id = "validation-message--success") private WebElement validationMessageSuccess;

    @FindBy(xpath = "id('content')/div[2]/div/div/p") private WebElement secondQuestionMessage;

    @FindBy(id = "legendQuestion") private WebElement legendQuestion;

    @FindBy(id = "question2") private WebElement question2;

    @FindBy(id = "submitSecurityQuestion") private WebElement submitSecurityQuestion;

    @FindBy(id = "validation-summary-message") private WebElement validationSummaryMessage;

    @FindBy(className = "validation-message") private WebElement validationMessage;

    public ForgotPwdSecurityQuesTwoPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public ForgotPwdSecurityQuesTwoPage(WebDriver driver, String PAGE_TITLE) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getValidationMessageSuccess() {
        return validationMessageSuccess.getText();
    }

    public String getSecondQuestionMessage() {
        return secondQuestionMessage.getText();
    }

    public String getLegendQuestion() {
        return legendQuestion.getText();
    }

    public String getValidationSummaryMessage() {
        return validationSummaryMessage.getText();
    }

    public String getValidationMessage() { return validationMessage.getText();}

    public ForgotPwdSecurityQuesTwoPage submitInvalidAnswer(String answer) {
        question2.clear();
        question2.sendKeys(answer);
        submitSecurityQuestion.click();
        return new ForgotPwdSecurityQuesTwoPage(driver);
    }

    public ForgotSecurityQuestionsPage submitInvalidAnswer3(String answer){
        question2.clear();
        question2.sendKeys(answer);
        submitSecurityQuestion.click();
        return new ForgotSecurityQuestionsPage(driver);
    }

    public ForgotPwdConfirmationPage submitValidAnswer(String answer) {
        question2.clear();
        question2.sendKeys(answer);
        submitSecurityQuestion.click();
        return new ForgotPwdConfirmationPage(driver);
    }

    public ResetSecurityPinConfirmationPage submitValidAnswer(String answer, String title){
        question2.clear();
        question2.sendKeys(answer);
        submitSecurityQuestion.click();
        return new ResetSecurityPinConfirmationPage(driver, PageTitles.RESET_PIN.getPageTitle());
    }
}
