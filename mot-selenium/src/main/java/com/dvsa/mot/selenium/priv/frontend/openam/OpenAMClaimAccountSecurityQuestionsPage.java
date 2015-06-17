package com.dvsa.mot.selenium.priv.frontend.openam;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

public class OpenAMClaimAccountSecurityQuestionsPage extends BasePage {

    @FindBy(id = "question_a") private WebElement securityQuestionsDropDownListA;

    @FindBy(id = "answer_a") private WebElement securityAnswerForTextFieldA;

    @FindBy(id = "question_b") private WebElement securityQuestionsDropDownListB;

    @FindBy(id = "answer_b") private WebElement securityAnswerForTextFieldB;

    @FindBy(id = "btSubmitForm") private WebElement submitFormButton;

    public OpenAMClaimAccountSecurityQuestionsPage(WebDriver driver) {
        super(driver);

    }

    public void selectSecurityQuestionA() {
        Select select = new Select(securityQuestionsDropDownListA);
        select.selectByVisibleText("What was your favourite place to visit as a child?");
    }

    public void enterAnswerForSecurityQuestionA() {
        securityAnswerForTextFieldA.sendKeys("");
    }

    public void selectSecurityQuestionB() {
        Select select = new Select(securityQuestionsDropDownListB);
        select.selectByVisibleText("What did you want to be when you grew up?");
    }

    public void enterAnswerForSecurityQuestionB() {
        securityAnswerForTextFieldB.sendKeys("");
    }

    public OpenAMClaimAccountMotTestPinPage clickOnSubmitButton() {
        submitFormButton.click();
        return new OpenAMClaimAccountMotTestPinPage(driver);
    }

    public OpenAMClaimAccountSecurityQuestionsPage setFirstSecurityQuestionAndAnswer() {
        selectSecurityQuestionA();
        enterAnswerForSecurityQuestionA();
        return this;
    }

    public OpenAMClaimAccountSecurityQuestionsPage setSecondSecurityQuestionAndAnswer() {
        selectSecurityQuestionB();
        enterAnswerForSecurityQuestionB();
        return this;
    }

    public OpenAMClaimAccountSecurityQuestionsPage setSecurityQuestionAndAnswersSuccessfully() {
        selectSecurityQuestionA();
        securityAnswerForTextFieldA.sendKeys("paris");
        selectSecurityQuestionB();
        securityAnswerForTextFieldB.sendKeys("Engineer");
        return this;
    }

    public OpenAMClaimAccountMotTestPinPage submitSecurityQuestionAndAnswersSuccessfully() {
        setSecurityQuestionAndAnswersSuccessfully();
        submitFormButton.click();
        return new OpenAMClaimAccountMotTestPinPage(driver);
    }

    public OpenAMClaimAccountSecurityQuestionsPage changeSecurityQuestionAndAnswers() {
        securityAnswerForTextFieldA.clear();
        securityAnswerForTextFieldA.sendKeys("Morocco");
        selectSecurityQuestionB();
        securityAnswerForTextFieldB.sendKeys("Doctor");
        return this;
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
